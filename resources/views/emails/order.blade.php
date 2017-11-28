@extends('emails.master')

@section('content')

<tr>
	<td>
        <p style="font-size:14px; margin-top:30px; margin-bottom: 20px;">Hi {{ $name }},</p>
        <p style="font-size:14px; margin-top:10px; margin-bottom: 20px;">Thank you for your order for J&auml;germeister labels</p>
	</td>
</tr>
@foreach($basket as $row)
<tr>
	<td>
		<table cellpadding="0" cellspacing="0" border="0" width="600" align="center">
			<tr>
				<td width="100">
				    <img src="{{ $row->options->imageurl }}" width="80" height="80" alt="{{ $row->name }}">
				</td>
				<td>
					<p style="margin-top:0;margin-bottom:5px;font-size:20px;color:#DD5A12;">{{ $row->name }}</p>
					<p style="margin-top:0;margin-bottom:5px;font-size:14px;">Quantity: {{ $row->qty }}</p>
					<p style="margin-top:0;margin-bottom:5px;font-size:14px;">Price: &pound;{{ $row->price }} (each)</p>
                    <p style="margin-top:0;margin-bottom:0px;font-size:14px;">Total Price: &pound;{{ $row->subtotal }}</p>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td>
		<img src="{{ asset('images/emails/hr.png') }}" style="display:block" border="0" />
	</td>
</tr>
@endforeach

@endsection