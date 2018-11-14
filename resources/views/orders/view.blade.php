@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Order id {{ $order->id }}</h1>
    
    <hr>
    
	<table class="table">
        <thead>
        	<tr>
                <th class="col-xs-6">Image</th>
                <th class="col-xs-6">Order Details</th>
            </tr>
        </thead>

		<tbody>
            @foreach($basket as $row)
            <tr>
                <td><img src="{{ $row['options']['imageurl'] }}" alt="{{ $row['name'] }}" class="img-responsive"></td>
                <td>
                    <p><strong>Product</strong>: {{ $row['name'] }}</p>
                    <p><strong>Quantity:</strong> {{ $row['qty'] }}</p>
                    <p><strong>Unit Price:</strong> £{{ $row['price'] }}</p>
                    <p><strong>Subtotal:</strong> £{{ $row['subtotal'] }}</p>
                    <br>
                    <br>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ action('OrderController@getOrders', ['id' => auth()->user()->id]) }}">Back to My Orders</a>
<!-- 
    @foreach($basket as $row)
    <div class="container-fluid">
    	<div class="col-xs-12 col-md-6">
    		<img class="img-responsive" src="{{ $row['options']['imageurl'] }}">
	    </div>
	    <div class="col-xs-12 col-md-6">
	    	<h3>Order Details</h3>
	    	Product: {{ $row['name'] }}
		    <br>
		    Price: £{{ $row['price'] }}
		    <br>
		    Subtotal: £{{ $row['subtotal'] }}
		    <br>
		    <br>
		    <a href="{{ action('OrderController@getOrders', ['id' => auth()->user()->id]) }}">Back to My Orders</a>
		</div>
    </div>
    @endforeach -->
    
</div>

@endsection