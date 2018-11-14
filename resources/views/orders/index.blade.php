@extends('layouts.app')

@section('content')

<div class="container">
    <h1>My Orders</h1>
    <h4>Click Customer Number to view order</h4>
    <hr>

    <table class="table">
		<thead>
			<tr>
				<th>Customer Number</th>
				<th>Customer Name</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>
    	@foreach($orders as $order)
			<tr>
				<td><a href="{{ action('OrderController@getOrder', ['id' => $order['id']]) }}">{{ $order['custnumber'] }}</a></td>
				<td>{{ $order['custname'] }}</td>
				<td>{{ $order['created_at'] }}</td>
			</tr>

    	@endforeach
    	</tbody>
	</table>
    
    
</div>

@endsection
