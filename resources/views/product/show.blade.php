@extends('layouts.app')

@section('content')

<div class="container">
    <h1>{{ $product->name }}</h1>
    
    <hr>
    
    <div class="row">
        <div class="col-md-5">
            <img src="{{ asset('products/' . $product->image) }}" alt="{{ $product->name }}" class="img-responsive">
        </div>
        
        <div class="col-md-7">
            {!! $product->description !!}
            
            <p>£{{ $product->price }}</p>
            
            @if($product->gateway)
            <p>You may personalise this product</p>
            
            <p><a href="{{ action('ProductController@personaliser', $product->id) }}" class="btn btn-primary">Personalise Now</a> </p>
            @endif
            
            @if($product->gatewaymulti)
            <p>This is a multi-part product: You may personalise all products in this pack</p>
            
            <p><a href="{{ action('ProductController@personaliser', [json_decode($product->gatewaymulti, true)[1], $product->id]) }}" class="btn btn-primary">Personalise Now</a> </p>
            @endif
            
            
        </div>
    </div>
</div>

@endsection
