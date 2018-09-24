@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Notebooks</h1>
    
    <hr>
    
    <div class="row">
        <?php $i = 1; ?>
        @foreach($nbProducts as $product)

        <div class="col-sm-3 text-center">
            <a href="{{ action('ProductController@show', $product->id)}}">
                <?php
                    if(strncmp($product->image, 'http', 4) === 0){
                        $imageurl = $product->image;
                    } else {
                        $imageurl = asset('products/' . $product->image);
                    }
                ?>
                <img src="{{ $imageurl }}" alt="{{ $product->name }}" class="img-responsive thumbnail">
                <div class="h3" style="margin-bottom: 20px;">{{ $product->name }}</div>
            </a>
        </div>

        <?php if($i % 4 === 0){ echo '<div class="clearfix"></div>'; } ?>
        <?php $i++; ?>
        @endforeach
        
    </div>
</div>

@endsection
