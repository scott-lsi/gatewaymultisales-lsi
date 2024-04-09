<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('updated_at', 'ASC')->get();
        
        return view('product.index', [
           'products' => $products,
        ]);
    }

    public function getTrashed()
    {
        // gets all the products that have been soft deleted
        $products = Product::onlyTrashed()->get();

        return view('product.trashed', [
            'products' => $products,
            'title' => 'Discontinued Products'
        ]);
    }


    
    public function getProductsByType($type)
    {
        $products = Product::where('category', 'like', '%%' . $type . '%%')->orderBy('name')->get();
        $title = $this->camelToNice($type);

        return view('product.productcat', [
           'products' => $products,
           'title' => $title,
        ]);
    }

    /*public function getProductsByCat($cat)
    {
        $nbProducts = Product::where('category', 'like', '%%' . $cat . '%%')->orderBy('name')->get();
        
        return view('product.notebooks', [
           'nbProducts' => $nbProducts,
        ]);
    }*/

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        $mobileUrl = 'https://app.gateway3d.com/acp/app/';
        $mobileUrl .= '?';
        $mobileUrl .= 'l=mobile';
        $mobileUrl .= '&';
        $mobileUrl .= 'c=7dzqyj5h26zljz4';
        $mobileUrl .= '#';
        $mobileUrl .= 'p=' . $product->gateway;
        $mobileUrl .= '&guid=' . env('GATEWAY_COMPANY');
        $mobileUrl .= '&r=multi';
        $mobileUrl .= '&epa=' . action('ProductController@getExternalPricingAPI', [$product->id]);
        $mobileUrl .= '&ep3dUrl=' . action('CartController@add');
        
        return view('product.show', [
           'product' => $product,
           'mobileUrl' => $mobileUrl,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function personaliser($id, $gatewaymultiId, $printJobRef = null, $rowIdToUpdate = null){
        $product = Product::find($id);

        $addToBasketUrl = action('CartController@add', [$gatewaymultiId, $rowIdToUpdate]);

//        $iframeOrigin = 'https://legacy.custom-gateway.net';
//        $iframeUrl = $iframeOrigin;
//        $iframeUrl .= '/acp/app/?l=acp3_2-staging';
//        $iframeUrl .= '&c=' . env('GATEWAY_CONFIG');
//        $iframeUrl .= '#p=' . $product->gateway;
//        if($printJobRef){
//            $iframeUrl .= '&pj=' . $printJobRef;
//        }
//        $iframeUrl .= '&guid=' . env('GATEWAY_COMPANY');
//        $iframeUrl .= '&r=2d-canvas';
//        $iframeUrl .= '&a2c=postMessage';
//        $iframeUrl .= '&epa=' . rawurlencode(action('ProductController@getExternalPricingAPI', $product->id));
//        $iframeUrl .= '&_usePs=1&_pav=3';

        $iframeOrigin = 'https://g3d-app.com';
        $iframeUrl = $iframeOrigin;
        $iframeUrl .= '/s/app/acp3_2/en_GB/';
        $iframeUrl .= env('GATEWAY_CONFIG') . '.html';
        $iframeUrl .= '#p=' . $product->gateway;
        if($printJobRef){
            $iframeUrl .= '&pj=' . $printJobRef;
        }
        $iframeUrl .= '&guid=' . env('GATEWAY_COMPANY');
        $iframeUrl .= '&r=2d-canvas';
        $iframeUrl .= '&a2c=postMessage';
//        $iframeUrl .= '&epa=' . rawurlencode(action('ProductController@getExternalPricingAPI', $product->id));
        $iframeUrl .= '&_usePs=1&_pav=3';
        
        return view('product.personaliser', [
            'iframeOrigin' => $iframeOrigin,
            'iframeUrl' => $iframeUrl,
            'addToBasketUrl' => $addToBasketUrl,
        ]);
    }
	
	public function getExternalPricingAPI($id){
		$callback = $_GET['callback'];
		$callback = preg_replace("/[^0-9a-zA-Z\$_]/", "", $callback); // XSS prevention
		
		$product = Product::find($id);
		
		$epaArray = [
			'name' => $product->name,
			'description' => $product->description,
		];
		$epaJson = json_encode($epaArray);
		
		header('Content-type: application/javascript'); // this was text/plain as per the docs, but on poshop digitalocean server it requires application/javascript in chrome
		echo "{$callback}({$epaJson})";
		exit;
	}

    private function camelToNice($str) {
        $output = preg_replace('/(?<=\\w)(?=[A-Z])/', " $1", $str);
        $output = trim($output);
        return ucwords($output);
    }
}
