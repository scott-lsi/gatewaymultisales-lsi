@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Basket</h1>
    
    <hr>
    
    <a href="{{ action('CartController@destroy')}}">Clear basket</a>
    
    <hr>
    
    <table class="table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($basket as $row)
            <tr>
                <td>{{ $row->name }}</td>
                <td>{{ $row->qty }}</td>
                <td>{{ $row->price }}</td>
                <td>{{ $row->subtotal }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right">Total:</td>
                <td>£{{ \Cart::total() }}</td>
            </tr>
        </tfoot>
    </table>
</div>

@endsection
