<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Cart;

use App\Product;
use App\Order;

class CartController extends Controller
{
    public function index(){
        $basket = Cart::content();
        
        return view('basket.index', [
            'basket' => $basket,
            'countries' => \App\Country::orderBy('langEN')->pluck('langEN', 'alpha2'),
        ]);
    }
    
    public function add(Request $request, $gatewaymultiId, $rowIdToUpdate = null){
        $gateway = $request->data;
        $options = [];

        // get the product form the db
        if(isset($gateway['extra']['state']['product_id'])){
            $product = Product::where('gateway', $gateway['extra']['state']['product_id'])->first();
        } else {
            $product = Product::where('sku', $gateway['sku'])->first();
        }

        // quantity
        $quantity = $gateway['quantity'];

        // aspects
        $aspects = [];
        if(isset($gateway['extra']['state']['aspects'][0]['aspect_id'])){
        	$aspects = [
        		'aspect_id' => $gateway['extra']['state']['aspects'][0]['aspect_id'],
        		'option_id' => $gateway['extra']['state']['aspects'][0]['option_id'],
        	];
        }

        // options
        $options['printjobref'] = $gateway['ref'];
        $options['imageurl'] = $gateway['thumbnails'][0]['url'];
        $options['aspects'] = $aspects;
        // options -> text inputs
        if(isset($gateway['extra']['state']['text_areas'])){
            $textInputs = [];
            foreach($gateway['extra']['state']['text_areas'] as $textarea){
                if(isset($textarea['text'])){
                    $textInputs[] = $textarea['text'];
                }
            }
            $options['textinputs'] = $textInputs;
            \Log::info($textInputs);
        }
        
        if($rowIdToUpdate){
            \Log::info('$rowIdToUpdate = ' . $rowIdToUpdate);
            $original_row = Cart::get($rowIdToUpdate);
            \Log::info($original_row);

            \Log::info('Updating');
            $updated_row = Cart::update($rowIdToUpdate, [
                'quantity' => $quantity,
                'options' => $options
            ]);
            \Log::info($updated_row);
        } else {
            \Log::info('Adding');
            Cart::add(
                $product->id, 
                $product->name, 
                $quantity, 
                $product->price,
                $options
            );
        }
        
        if($gatewaymultiId > 0){
            // it's a gatewaymulti product
            $gatewaymultiProduct = Product::find($gatewaymultiId);
            $gatewaymultiGateways = json_decode($gatewaymultiProduct->gatewaymulti, true);
            
            $thisId = array_search($product->id, $gatewaymultiGateways);
        
            if(array_key_exists($thisId+1, $gatewaymultiGateways)){
                return action('ProductController@personaliser', [$gatewaymultiGateways[$thisId+1], $gatewaymultiId]);
            }
        }
        
        return action('CartController@index');
    }

    public function update(Request $request, $rowIdToUpdate){
        $gateway = $request->data;
        $options = [];

        // get what's been typed in
        if(isset($gateway['extra']['state']['text_areas'])){
            $textInputs = [];
            foreach($gateway['extra']['state']['text_areas'] as $textarea){
                if(isset($textarea['text'])){
                    $textInputs[] = $textarea['text'];
                }
            }
            $options['textinputs'][] = $textInputs;
        }

        // get the product form the db
        if(isset($gateway['extra']['state']['product_id'])){
            $product = Product::where('gateway', $gateway['extra']['state']['product_id'])->first();
        } else {
            $product = Product::where('sku', $gateway['sku'])->first();
        }

        // quantity
        $quantity = $gateway['quantity'];

        // aspects
        $aspects = [];
        if(isset($gateway['extra']['state']['aspects'][0]['aspect_id'])){
        	$aspects = [
        		'aspect_id' => $gateway['extra']['state']['aspects'][0]['aspect_id'],
        		'option_id' => $gateway['extra']['state']['aspects'][0]['option_id'],
        	];
        }

        // options
        $options = [];
        $options['printjobref'] = $gateway['ref'];
        $options['imageurl'] = $gateway['thumbnails'][0]['url'];
        $options['textinputs'] = $textInputs;
        $options['aspects'] = $aspects;

        Cart::update($rowIdToUpdate, [
            $product->id, 
            $product->name, 
            $quantity, 
            $product->price,
            $options
        ]);

        return action('CartController@index');
    }
    
    public function destroy(){
        Cart::destroy();
        
        return redirect()->back();
    }
	
	public function getRemoveItem($rowId){
		// get the row & remove it
		\Cart::remove($rowId);
		
		// message
		\Session::flash('message', 'Item removed');
		\Session::flash('alert-class', 'alert-success');
		
		// go to the basket
		return redirect()->back();
	}
    
    public function postUpdateQty(Request $request, $rowId){
		Cart::update($rowId, [
			'qty' => $request->input('qty'),
		]);
        
        return redirect()->back();
    }
    
	public function postToPrint(Request $request){
        // prepare the artwork
        $g3d = $this->gatewayPrepare($request, $request->input('custnumber')); 
        
        // create a new order for the db        
        $order = new Order;
        $order->user_id = $request->input('user_id');
        $order->name = $request->input('name');
        $order->custnumber = $request->input('custnumber');
        $order->custname = $request->input('custname');
        $order->moreinfo = $request->input('moreinfo');
        $order->email = $request->input('email');
        $order->basket = json_encode(Cart::content());
        $order->g3d = $g3d;
        
        // email
        $view_data = [
            'name' => $request->name,
            'email' => $request->email,
            'custnumber' => $request->custnumber,
            'custname' => $request->custname,
            'moreinfo' => $request->moreinfo,
            'deldate' => $request->deldate,
            'basket' => \Cart::Content(),
        ];
        $email_data = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        
        // save it all and send things
        $order->save();
        $this->gatewaySend($g3d);
        // dd($this->gatewaySend($g3d));
        $this->sendOrderEmail($view_data, $email_data);
        
        Cart::destroy();
        
        return view('basket.complete', [
            'order' => $order,
        ]);
    }
    
    private function gatewayPrepare(Request $request, $custnumber){
    	$rnd = Str::random(8);
        $gatewayArray = [
            'external_ref' => $request->input('custnumber') . '-' . $rnd,
            'company_ref_id' => env('GATEWAY_COMPANY'),
            'sale_datetime' => date('Y-m-d H:i:s'),
            
            'customer_name' => $request->input('name'),
            'customer_email' => $request->input('email'),
			
			'shipping_address_1' =>	$request->input('custname'),
			'shipping_address_2' =>	$request->input('moreinfo'),
			'shipping_address_3' =>	'',
			'shipping_address_4' =>	'',
			'shipping_address_5' =>	'',
			'shipping_postcode' =>	'',
			'shipping_country' =>	'',
			'shipping_country_code' => 'GB',
			
			'shipping_method' =>	'',
			'shipping_carrier' =>	'',
			'shipping_tracking' =>	'',
			
			'billing_address_1' =>	'',
			'billing_address_2' =>	'',
			'billing_address_3' =>	'',
			'billing_address_4' =>	'',
			'billing_address_5' =>	'',
			'billing_postcode' =>	'',
			'billing_country' =>	'',
			
			'payment_trans_id' =>	'',
			
			'items' =>				[],
        ];
        
        $i = 1;
        $items = [];
        foreach(Cart::content() as $row){
            if($row->options->printjobref){
                $product = \App\Product::find($row->id);
                
                $productArray = [
                    'sku' => $product->sku,
                    'external_ref' => $custnumber . '-' . $rnd . '-' . $i,
                    'description' => $row->name,
                    'quantity' => $row->qty,
                    'type' => 2, // 2 = Print Job (http://developers.gateway3d.com/Print-iT_Integration#Item_Type_Codes)
                    'print_job_id' => $row->options->printjobid,
                    'print_job_ref' => $row->options->printjobref,
                    'unit_sale_price' => $row->price,
                    'aspects' => [$row->options->aspects],
                ];
				
				$items[] = $productArray;
				$i++;
            }
        }
        
        // put the items in the array
		$gatewayArray['items'] = array_merge($gatewayArray['items'], $items);
        
        // gateway wants it in json format
		return json_encode($gatewayArray);
    }
    
    private function gatewaySend($order){
        $gatewayUrl = env('GATEWAY_API_URL');
        
		$curl = curl_init($gatewayUrl);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, true);

		$headers = array();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Authorization: Basic 13064:d70hxn0y03wyhq5g0d887r855p9';

		// dd($headers);

		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $order);
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);

		$gatewayResponse = curl_exec($curl);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		// $info = curl_getinfo($curl);
		
		// echo base64_decode('MTMwNjQ6ZDcwaHhuMHkwM3d5aHE1ZzBkODg3cjg1NXA=');
		//var_dump($info['request_header']);
		//return;

		if($status != 200) {
			die("Error: call to URL $gatewayUrl failed with status $status, response $gatewayResponse, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
		}

		curl_close($curl);
		
    }
    
    private function sendOrderEmail($view_data, $email_data){
        if(strpos(env('ORDER_MAIL_BCC', false), ',')){
            $bcc = explode(',', env('ORDER_MAIL_BCC', false));
        } else {
            $bcc = env('ORDER_MAIL_BCC', false);
        }
        
        \Mail::send('emails.order', $view_data, function($message) use($email_data, $bcc) {
            $message->to($email_data['email'], $email_data['name'])
                    ->bcc($bcc)
                    ->subject(env('EMAIL_ORDER_SUBJECT'));
        });
    }
}
