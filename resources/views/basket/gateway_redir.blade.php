@extends('layouts.app')

@section('title', 'Processing Artwork')

@section('content')

<script type='text/javascript'>
    setTimeout(function(){
        window.parent.location.href = "{{ action('CartController@index') }}"
    }, 2000);
</script>

<div class="container text-center">
    <h1>Processing Artwork</h1>
    <br>
    <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
</div>

@endsection