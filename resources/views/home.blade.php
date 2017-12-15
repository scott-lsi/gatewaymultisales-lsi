@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Welcome to the J&auml;germeister Label Personaliser</h1>
    
    <hr>
    
    {{ Cookie::get('accesscode') }}
    
    @if(Cookie::get('accesscode') !== env('ACCESS_CODE'))
    <div class="row">
        <div class="col-sm-6 col-md-4 col-lg-3">
            {!! Form::open(['action' => 'PageController@postAccessCode']) !!}
                <div class="form-group">
                    {!! Form::label('accesscode', 'Please enter your access code') !!}
                    {!! Form::text('accesscode', null, ['class' => 'form-control']) !!}
                </div>
                {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
        </div>
    </div>
    @else
    You are logged in
    @endif
    
    
</div>

@endsection
