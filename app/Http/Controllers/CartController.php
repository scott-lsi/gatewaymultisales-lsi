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
        $gatewayArray = [
            'external_ref' => $request->session()->get('orderId'),
            'company_ref_id' => env('GATEWAY_COMPANY'),
            'sale_datetime' => date('Y-m-d H:i:s'),
            
            'customer_name' => $request->input('dispatch-firstname') . ' ' . $request->input('dispatch-lastname'),
            'customer_email' => $request->input('email'),
			
			'shipping_address_1' =>	$request->input('dispatch-companyname'),
			'shipping_address_2' =>	$request->input('dispatch-line1'),
			'shipping_address_3' =>	$request->input('dispatch-line2'),
			'shipping_address_4' =>	$request->input('dispatch-city'),
			'shipping_address_5' =>	'',
			'shipping_postcode' =>	$request->input('dispatch-postcode'),
			'shipping_country' =>	$request->input('dispatch-country'),
			'shipping_country_code' => $request->input('dispatch-country'),
			
			'shipping_method' =>	'',
			'shipping_carrier' =>	'',
			'shipping_tracking' =>	'',
			
			'billing_address_1' =>	$request->input('invoice-companyname'),
			'billing_address_2' =>	$request->input('invoice-line1'),
			'billing_address_3' =>	$request->input('invoice-line2'),
			'billing_address_4' =>	$request->input('invoice-city'),
			'billing_address_5' =>	'',
			'billing_postcode' =>	$request->input('invoice-postcode'),
			'billing_country' =>	$request->input('invoice-country'),
			
			'payment_trans_id' =>	'0123456789ABC',
			
			'items' =>				[],
        ];
        
        $i = 1;
        $items = [];
        foreach(\Basket::content() as $row){
            if($row->options->printjobid){
                $product = \App\Product::where('ownArticleNumber', $row->id)->first();
                
                $productArray = [
                    'sku' => $product->ownArticleNumber,
                    'external_ref' => $request->session()->get('orderId') . '-' . str_pad($i, 2, '0', STR_PAD_LEFT),
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
		curl_setopt($curl, CURLOPT_POSTFIELDS, $order->gateway);

		$gatewayResponse = curl_exec($curl);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if($status != 201) {
			die("Error: call to URL $url failed with status $status, response $g3d_json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
		}

		curl_close($curl);
		/**** send the order to g3d end ****/
		
		$order->gateway_response = $gatewayResponse;
		$order->save();
    }
}
