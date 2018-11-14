<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getOrders($id){
    	$user = \App\User::find($id);
    	$orders = json_decode($user->orders, true);

    	return view('orders.index', [
    		'orders' => $orders,
    	]);
    }

    public function getOrder($id){
    	$order = \App\Order::find($id);

    	return view('orders.view', [
    		'order' => $order,
    		'basket' => json_decode($order->basket, true), 	
    	]);
    }
}
