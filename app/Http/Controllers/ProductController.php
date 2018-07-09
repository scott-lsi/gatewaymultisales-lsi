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
        $products = Product::all();
        
        return view('product.index', [
           'products' => $products,
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
        
        return view('product.show', [
           'product' => $product,
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
    
    public function personaliser($id, $gatewaymultiId = null){
        $product = Product::find($id);
        
        if(\App::environment('local')) { $iframeUrl = 'https://app.gateway3d.com/acp/app/?l=acp3'; }
        else { $iframeUrl = 'https://my.gateway3d.com/acp/app/?l=acp3'; }
        $iframeUrl .= '&c=' . env('GATEWAY_CONFIG');
        $iframeUrl .= '#p=' . $product->gateway;
        $iframeUrl .= '&guid=' . env('GATEWAY_COMPANY');
        $iframeUrl .= '&r=2d-canvas';
		$iframeUrl .= '&epa=' . action('ProductController@getExternalPricingAPI', $product->id);
        $iframeUrl .= '&ep3dUrl=' . action('CartController@add', [$gatewaymultiId]);
        
        return view('product.personaliser', [
            'product' => $product,
            'iframeUrl' => $iframeUrl,
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
