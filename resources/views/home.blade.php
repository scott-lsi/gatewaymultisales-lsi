@extends('layouts.app')

@section('content')

<div class="container">
    <h1>LSi Print Creatorrrrr</h1>
    
    <hr>
    
    @if(session('accesscode') !== env('ACCESS_CODE'))
        {{ Form::open() }}
        <h1>Login Here</h1>

        <!-- if there are login errors, show them here -->
        <p>
            {{ $errors->first('email') }}
            {{ $errors->first('password') }}
        </p>

        <p>
            {{ Form::label('email', 'Email Address') }}
            {{ Form::text('email', Input::old('email'), array('placeholder' => 'awesome@awesome.com')) }}
        </p>

        <p>
            {{ Form::label('password', 'Password') }}
            {{ Form::password('password') }}
        </p>

        <p>{{ Form::submit('Submit!') }}</p>
        {{ Form::close() }}
    @else
        <h3>You are logged in</h3>
        <p><a href="{{ action('ProductController@index') }}" class="btn btn-md btn-primary">Click to view the product list</a></p>
    @endif
    
    
</div>

@endsection
