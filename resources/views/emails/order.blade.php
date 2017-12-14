@extends('emails.master')

@section('title', 'Label Order')

@section('content')

<tr>
	<td>
        <p style="font-size:14px; margin-top:30px; margin-bottom:20px;">Hi {{ $name }},</p>
        <p style="font-size:14px; margin-top:10px; margin-bottom:20px;">Thank you for your order for J&auml;germeister labels</p>
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
					<p style="margin-top:0;margin-bottom:5px;font-size:14px;">Price: &pound;{{ number_format($row->price, 2) }} (each)</p>
                    <p style="margin-top:0;margin-bottom:0px;font-size:14px;">Total Price: &pound;{{ number_format($row->subtotal) }}</p>
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

<tr>
	<td>
        <p style="font-size:14px; margin-top:30px; margin-bottom:5px; font-weight:bold;">Delivery Address:</p>
        <p style="font-size:14px; margin-top:10px; margin-bottom:20px;">
            @if($add1) {{ $add1 }}<br />@endif
            @if($add2) {{ $add2 }}<br />@endif
            @if($add3) {{ $add3 }}<br />@endif
            @if($add4) {{ $add4 }}<br />@endif
            @if($postcode) {{ $postcode }}<br />@endif
            @if($country) {{ $country }}<br />@endif
        </p>
        
        @if($deliverydate)
        <p style="font-size:14px; margin-top:10px; margin-bottom:5px; font-weight:bold;">You have specified an event date:</p>
        <p style="font-size:14px; margin-top:10px; margin-bottom:20px;">{{ date('d-m-Y', strtotime($deliverydate)) }}</p>
        @endif
        
        @if($notes)
        <p style="font-size:14px; margin-top:10px; margin-bottom:5px; font-weight:bold;">You have added a note, stating:</p>
        <p style="font-size:14px; margin-top:10px; margin-bottom:20px;">{!! nl2br($notes) !!}</p>
        @endif
	</td>
</tr>

<tr>
	<td>
		<img src="{{ asset('images/emails/hr.png') }}" style="display:block" border="0" />
	</td>
</tr>
<tr>
	<td>
        <p style="font-size:11px; margin-top:30px; margin-bottom:20px;">
            AWRS No: XMAW00000100010<br />
            VAT Registration No: GB168857155
        </p>
	</td>
</tr>

@endsection