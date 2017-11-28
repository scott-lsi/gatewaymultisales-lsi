<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cart;
use App\Product;

class CartController extends Controller
{
    public function index(){
        $basket = Cart::content();
        
        return view('basket.index', [
            'basket' => $basket,
            'countries' => \App\Country::orderBy('langEN')->pluck('langEN', 'alpha2'),
        ]);
    }
    
    public function add(Request $request, $gatewaymultiId = null){
        // artwork
        $gateway = $this->gatewayAdd($_POST['data']);
        $product = Product::where('sku', $gateway->sku)->first();

        // quantity
        $quantity = $gateway->quantity;

        // options
        $options = [];
        $options['printjobid'] = $gateway->printJobId;
        $options['imageurl'] = $gateway->thumburl;
        
        Cart::add(
            $product->id, 
            $product->name, 
            $quantity, 
            $product->price, 
            $options
        );
        
        if($gatewaymultiId){
            // it's a gatewaymulti product
            $gatewaymultiProduct = Product::find($gatewaymultiId);
            $gatewaymultiGateways = json_decode($gatewaymultiProduct->gatewaymulti, true);
            
            var_dump($gatewaymultiGateways);
            $thisId = array_search($product->id, $gatewaymultiGateways);
        
            if(array_key_exists($thisId+1, $gatewaymultiGateways)){
                /*echo $gatewaymultiGateways[$thisId+1];
                echo '<br>';
                echo $gatewaymultiId;
                return;*/
                return redirect()->action('CartController@gatewayRedir', [$gatewaymultiGateways[$thisId+1], $gatewaymultiId]);
            }
        }
        
        return redirect()->action('CartController@gatewayRedir');
    }
    
    public function gatewayRedir($id = null, $gatewaymultiId = null){
        if($id && $gatewaymultiId){
            return view('basket.gatewaymulti_redir', [
                'redirUrl' => action('ProductController@personaliser', [$id, $gatewaymultiId]),
            ]);
        } else {
            return view('basket.gateway_redir');
        }
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
        $validatorRules = [
            'name' =>               'required',
            'email' =>              'required|email',
            'recipient' =>          'required',
            'add1' =>               'required',
            'add3' =>               'required',
            'postcode' =>           'required',
            'country' =>            'required',
            'terms' =>              'required',
        ];
        
        $validatorMessages = [
            'name.required' => 'Please enter your name',
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'recipient.required' => 'Please ensure you have entered a recipient name',
            'add1.required' => 'Please ensure you have entered at least the 1st line of the delivery address',
            'add3.required' => 'Please ensure you have entered at town/city of the delivery address',
            'postcode.required' => 'Please ensure you have entered at least the postcode of the delivery address',
            'country.required' => 'Please choose a country from the delivery address dropdown menu',
            'terms.required' => 'Please indicate that you have checked any uploaded logos and are happy with the quality',
        ];
        
        $validator = \Validator::make($request->all(), $validatorRules, $validatorMessages);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $order = $this->gatewayPrepare($request);
        $this->gatewaySend($order);
        
        // email
        $view_data = [
            'name' => $request->input('name'),
            'basket' => \Cart::Content(),
        ];
        $email_data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];
        
        $this->sendOrderEmail($view_data, $email_data);
        
        Cart::destroy();
        
        return view('basket.complete', [
            'order' => $order,
        ]);
    }
    
    private function gatewayAdd($data){
        $type = $_SERVER['CONTENT_TYPE'];
        switch($type)
        {
            case 'application/json':
                $json = file_get_contents('php://input');
                break;

            case 'application/x-www-form-urlencoded':
                $json = $data;
                break;

            default:
                throw new Exception('Invalid content-type');
        }

        return json_decode($json);
    }
    
    private function gatewayPrepare(Request $request){
        $ordernumber = 'JG' . str_random(8);
        
        $gatewayArray = [
            'external_ref' => $ordernumber,
            'company_ref_id' => env('GATEWAY_COMPANY'),
            'sale_datetime' => date('Y-m-d H:i:s'),
            
            'customer_name' => $request->input('recipient'),
            'customer_email' => $request->input('email'),
			
			'shipping_address_1' =>	$request->input('add1'),
			'shipping_address_2' =>	$request->input('add2'),
			'shipping_address_3' =>	$request->input('add3'),
			'shipping_address_4' =>	$request->input('add4'),
			'shipping_address_5' =>	'',
			'shipping_postcode' =>	$request->input('postcode'),
			'shipping_country' =>	$request->input('country'),
			'shipping_country_code' => $request->input('country'),
			
			'shipping_method' =>	'',
			'shipping_carrier' =>	'',
			'shipping_tracking' =>	'',
			
			'billing_address_1' =>	$request->input('name') . '(' . $request->input('email') . ')',
			'billing_address_2' =>	$request->input('deliverydate'),
			'billing_address_3' =>	$request->input('notes'),
			'billing_address_4' =>	'',
			'billing_address_5' =>	'',
			'billing_postcode' =>	'',
			'billing_country' =>	'',
			
			'payment_trans_id' =>	'0123456789ABC',
			
			'items' =>				[],
        ];
        
        $i = 1;
        $items = [];
        foreach(Cart::content() as $row){
            if($row->options->printjobid){
                $product = \App\Product::find($row->id);
                
                $productArray = [
                    'sku' => $product->sku,
                    'external_ref' => $ordernumber . '-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'description' => $row->name,
                    'quantity' => $row->qty,
                    'type' => 2, // 2 = Print Job (http://developers.gateway3d.com/Print-iT_Integration#Item_Type_Codes)
                    'print_job_id' => $row->options->printjobid,
                    'unit_sale_price' => $row->price,
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
        $gatewayUrl = 'https://my.gateway3d.com/acp/api/sl/2.1/order/?k=' . env('GATEWAY_API_KEY');
        
		$curl = curl_init($gatewayUrl);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,
			array("Content-type: application/json"));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $order);

		$gatewayResponse = curl_exec($curl);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if($status != 201) {
			die("Error: call to URL $url failed with status $status, response $g3d_json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
		}

		curl_close($curl);
		/**** send the order to g3d end ****/
		
		/*$order->gateway_response = $gatewayResponse;
		$order->save();*/
    }
    
    private function sendOrderEmail($view_data, $email_data){
        \Mail::send('emails.order', $view_data, function($message) use($email_data) {
            $message->to($email_data['email'], $email_data['name'])
                    ->bcc(env('ORDER_MAIL_BCC', false))
                    ->subject('Your order for Jägermeister labels');
        });
    }
}
