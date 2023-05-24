<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function logout(){
        // session()->forget('');
        // return redirect()->back();

        Auth::logout(); // Log out user
        //return Redirect::to('login');
        return redirect()->action('HomeController@index');
    }

    public function getCreatePcs()
    {
        return view('pcs');
    }

    public function postCreatePcs(Request $request)
    {
        $external_gallery_creation_request = Http::withHeaders(['X-Authorization' => env('LSI_API_KEY')]);
        foreach($request->logos as $i=>$logo){
            $external_gallery_creation_request = $external_gallery_creation_request->attach('logos[]', fopen($logo, 'r'), $request->subdomain . '-logo-' . $i . '.' . $logo->getClientOriginalExtension());
        }

        $external_gallery_creation_response = $external_gallery_creation_request->post(env('LSI_API_URL') . '/kornit/externalgallery/create', [
            'site_subdomain' => $request->subdomain,
        ]);

        return $external_gallery_creation_response->body();
        die();

        if(!$external_gallery_creation_response->successful()){
            session()->flash('message', [
                'type' => json_decode($external_gallery_creation_response->body())->status != 'success' ? 'danger' : 'success',
                'content' => json_decode($external_gallery_creation_response->body())->message
            ]);
            
            return back()->withInput();
        }

        $create_pcs_response = Http::withHeaders(['X-Authorization' => env('LSI_API_KEY')])->post(env('LSI_API_URL') . '/pcs/create', [
            'subdomain' => $request->subdomain,
            'colour' => $request->colour,
            'salesperson_email' => $request->salesperson_email,
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
        ]);

        session()->flash('message', [
            'type' => json_decode($create_pcs_response->body())->status != 'success' ? 'danger' : 'success',
            'content' => json_decode($create_pcs_response->body())->message
        ]);
            
        return back();
    }
}