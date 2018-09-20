@extends('layouts.app')

@section('content')

<div class="container">
    <h1>LSi Print Creator</h1>
    
    <hr>
    
    <h3>You are logged in</h3>
    <p><a href="{{ action('ProductController@index') }}" class="btn btn-md btn-primary">Click to view the product list</a></p>
    
    
</div>

@endsection
