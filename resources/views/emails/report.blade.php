@extends('emails.master')

@section('title', 'Report')

@section('content')

<tr>
	<td>
        <p style="font-size:14px; margin-top:30px; margin-bottom:20px;">Hi,</p>
        <p style="font-size:14px; margin-top:10px; margin-bottom:20px;">Please find attached the report for the previous month for J&auml;germeister labels</p>
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