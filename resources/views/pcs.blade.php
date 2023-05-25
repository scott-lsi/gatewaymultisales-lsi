@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.css"/>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.js"></script>
<script>
    Coloris({
        alpha: false
    });
</script>
@endpush

@section('content')

<div class="container">
    <h1>Create a Personalised Customer Site</h1>
    
    <hr>
    
    <form action="{{ action('HomeController@postCreatePcs') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="salesperson_email">Your Email</label>
            <input type="text" class="form-control" id="salesperson_email" name="salesperson_email" value="{{ old('salesperson_email') ? old('salesperson_email') : auth()->user()->email }}" required>
        </div>

        <hr>

        <div class="form-group">
            <label for="subdomain">Subdomain</label>
            <input type="text" class="form-control" id="subdomain" name="subdomain" value="{{ old('subdomain') }}" required>
            <p class="help-block">A site will be created at https://[subdomain].{{ env('PCS_DOMAIN') }}.</p>
        </div>

        <hr>

        <div class="form-group">
            <label for="user_name">Customer Name</label>
            <input type="text" class="form-control" id="user_name" name="user_name" value="{{ old('user_name') }}" required>
        </div>

        <div class="form-group">
            <label for="user_email">Customer Email</label>
            <input type="text" class="form-control" id="user_email" name="user_email" value="{{ old('user_email') }}" required>
        </div>

        <hr>

        <div class="form-group">
            <label for="colour">Colour</label><br>
            <input type="text" class="form-control" id="colour" name="colour" value="{{ old('colour') }}" required data-coloris>
            <p class="help-block">This <u>must</u> be a hex reference. Please use the colour picker to choose one or enter the reference if you know it, with the '#' in front of it. The site will be styled using this colour. Pick something good for the company you're making this for.</p>
        </div>

        <div class="form-group">
            <label for="colour">Logo(s)</label>
            <input type="file" id="logos" name="logos[]" multiple required>
            <p class="help-block">Please upload a transparent PNG file for best results. Max file size, 2MB.</p>
        </div>

        <hr>

        <button class="btn btn-primary">Submit</button>
    </form>
</div>

@endsection
