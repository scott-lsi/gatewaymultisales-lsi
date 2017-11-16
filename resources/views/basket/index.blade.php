@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Basket</h1>
    
    <hr>
    
    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <hr>
    @endif
    
    <div class="row">
        <div class="col-md-9">
            <table class="table">
                <thead>
                    <tr>
                        <th class="col-xs-4">Image</th>
                        <th class="col-xs-4">Product Name</th>
                        <th class="col-xs-2">Price</th>
                        <th class="col-xs-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($basket as $row)
                    <tr>
                        <td><img src="{{ $row->options->imageurl }}" alt="{{ $row->name }}" class="img-responsive"></td>
                        <td>
                            <p>{{ $row->name }}</p>
                            
                            {!! Form::open(['action' => ['CartController@postUpdateQty', $row->rowId]]) !!}
                            <div class="row">
                                <div class="col-xs-6">
                                    {!! Form::hidden('productId', $row->id) !!}
                                    {!! Form::number('qty', $row->qty, ['class' => 'input-sm form-control']) !!}
                                </div>
                                <div class="col-xs-6">
                                    {!! Form::submit('Update Qty', ['class' => 'btn btn-primary btn-sm']) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                            
                            <p><small><a href="{{ action('CartController@getRemoveItem', ['rowId' => $row->rowId]) }}">Remove</a></small></p>
                        </td>
                        <td>{{ $row->price }}</td>
                        <td>{{ $row->subtotal }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right">Total:</td>
                        <td>£{{ \Cart::total() }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="col-md-3">
            @if($basket->count() >= 1)
            <div class="panel panel-primary">
                <div class="panel-heading">Just a few details&hellip;</div>
                <div class="panel-body">
                    {!! Form::open(['action' => 'CartController@postToPrint']) !!}
                        <div class="form-group">
                            <label for="name">Your Name *</label>
                            {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
                        </div>

                        <div class="form-group">
                            <label for="email">Your Email Address *</label>
                            {!! Form::text('email', null, ['class' => 'form-control', 'id' => 'email']) !!}
                        </div>
                    
                        <hr>

                        <div class="form-group">
                            <label for="email">Address Line 1 *</label>
                            {!! Form::text('add1', null, ['class' => 'form-control', 'id' => 'add1']) !!}
                        </div>

                        <div class="form-group">
                            <label for="email">Address Line 2</label>
                            {!! Form::text('add2', null, ['class' => 'form-control', 'id' => 'add2']) !!}
                        </div>

                        <div class="form-group">
                            <label for="email">Town/City *</label>
                            {!! Form::text('add3', null, ['class' => 'form-control', 'id' => 'add3']) !!}
                        </div>

                        <div class="form-group">
                            <label for="email">County</label>
                            {!! Form::text('add4', null, ['class' => 'form-control', 'id' => 'add4']) !!}
                        </div>

                        <div class="form-group">
                            <label for="email">Postcode *</label>
                            {!! Form::text('postcode', null, ['class' => 'form-control', 'id' => 'postcode']) !!}
                        </div>

                        <hr>

                        {!! Form::submit('Place Order', ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
            @endif
        </div>
    </div>
    
    
</div>

@endsection
