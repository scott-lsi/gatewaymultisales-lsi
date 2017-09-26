@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Products</h1>
    
    <hr>
    
    <div class="row">
        
    </div>
        @foreach($products as $product)
        <div class="col-sm-3 text-center">
            <a href="{{ action('ProductController@show', $product->id)}}">
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-responsive">
                <div class="h3">{{ $product->name }}</div>
            </a>
        </div>
        @endforeach
    </ul>
</div>

@endsection
