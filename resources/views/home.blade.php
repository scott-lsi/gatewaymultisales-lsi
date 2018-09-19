@extends('layouts.app')

@section('content')

<div class="container">
    <h1>LSi Print Creator - Login</h1>
    
    <hr>
    
    {{ Form::open() }}
    <h1>Login</h1>

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
    
    
</div>

@endsection