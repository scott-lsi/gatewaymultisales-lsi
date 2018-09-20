<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;

// class PageController extends Controller
// {
//     public function home(){
//         return view('home');
//     }
    
//     public function postAccessCode(Request $request){
//         if($request->accesscode === env('ACCESS_CODE')){
//             session(['accesscode' => $request->accesscode]);
//             return redirect()->action('ProductController@index');
//         } else {
//             $request->session()->flash('message.content', 'Your access code is incorrect. Please try again.');
//             $request->session()->flash('message.type', 'danger');
//             return redirect()->back();
//         }
//     }
    
//     public function logout(){
//         session()->forget('accesscode');
//         return redirect()->back();
//     }
    
//     public function test(){
//         echo env('ORDER_MAIL_BCC', false) . '<br>';
//         echo env('ORDER_MAIL_BCC');
        
//         if(strpos(env('ORDER_MAIL_BCC', false), ',')){
//             $bcc = explode(',', env('ORDER_MAIL_BCC', false));
//         } else {
//             $bcc = env('ORDER_MAIL_BCC', false);
//         }
        
//         //dd($bcc);
        
//         $view_data = [];
//         $email_data = [];
        
//         \Mail::send('emails.test', $view_data, function($message) use($email_data, $bcc) {
//             $message->to('tuestunim@gmail.com', 'Scott Brown')
//                     ->bcc($bcc)
//                     ->subject('Jägermeister labels test email');
//         });
//     }
// }

?>
