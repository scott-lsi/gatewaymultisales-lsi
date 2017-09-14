@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Products</h1>
    
    <hr>
    
    <ul>
        @foreach($products as $product)
        <li><a href="{{ action('ProductController@show', $product->id)}}">{{ $product->name }}</a></li>
        @endforeach
    </ul>
</div>

@endsection
