<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use Maatwebsite\Excel\Facades\Excel;

use App\Order;
use App\Exports\OrderExport;
use App\Mail\OrderReport;

class ExportController extends Controller
{
    public function exportOrders($email = null){
        $today = new \Datetime();
        $onemonth = new \DateInterval('P1M');
        $today_formatted = $today->format('Y-m-d');
        $monthago_formatted = $today->sub($onemonth)->format('Y-m-d');
        
        $filename = 'LSI_SAMPLES_' . $monthago_formatted . '-' . $today_formatted . '.xlsx';

        $store = Excel::store(new OrderExport($monthago_formatted, $today_formatted), $filename, 'exports');

        if($email){
            $to = $email;
        } else {
            $to = explode(',', env('REPORT_EMAIL'));
        }

        Mail::to($to)
            ->send(new OrderReport($filename));

        return 'done';
    }
}
