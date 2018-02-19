@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Welcome to the J&auml;germeister Label Personaliser</h1>
    
    <hr>
    
    <div class="row">
        
    </div>
        @foreach($products as $product)
        <div class="col-sm-3 text-center">
            <a href="{{ action('ProductController@show', $product->id)}}">
                <?php
                    if(strncmp($product->image, 'http', 4) === 0){
                        $imageurl = $product->image;
                    } else {
                        $imageurl = asset('products/' . $product->image);
                    }
                ?>
                <img src="{{ $imageurl }}" alt="{{ $product->name }}" class="img-responsive">
                <div class="h3">{{ $product->name }}</div>
            </a>
        </div>
        @endforeach
    </ul>
</div>

@endsection
