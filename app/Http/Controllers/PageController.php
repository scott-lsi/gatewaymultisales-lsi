<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home(){
        return view('home');
    }
    
    public function postAccessCode(Request $request){
        if($request->accesscode === env('ACCESS_CODE')){
            session(['accesscode' => $request->accesscode]);
            return redirect()->action('ProductController@index');
        } else {
            $request->session()->flash('message.content', 'Your access code is incorrect. Please try again.');
            $request->session()->flash('message.type', 'danger');
            return redirect()->back();
        }
    }
    
    public function logout(){
        session()->forget('accesscode');
        return redirect()->back();
    }
}
