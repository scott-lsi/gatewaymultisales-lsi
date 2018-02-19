@extends('layouts.app')

@section('title', 'Processing Artwork')

@section('content')

<script type='text/javascript'>
    setTimeout(function(){
        window.parent.location.href = "{{ $redirUrl }}"
    }, 2000);
</script>

<div class="container text-center">
    <h1>Processing Artwork</h1>
    <p class="lead">Your next product is coming up...</p>
    <br>
    <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
</div>

@endsection