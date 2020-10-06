<?php

namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

use App\Order;

class PerProductSheet implements FromView, WithTitle
{
    private $date_from;
    private $date_to;

    public function __construct(string $monthago_formatted, string $today_formatted)
    {
        $this->monthago_formatted = $monthago_formatted;
        $this->today_formatted = $today_formatted;
    }

    /**
     * @return Builder
     */
    public function view(): View
    {
        $orders = Order::where('created_at', '>=', $this->monthago_formatted)->where('created_at', '<=', $this->today_formatted)->get();
        
        $per_product = [];
        foreach($orders as $order){
            // basket & basket item list
            $basket = json_decode($order->basket, true);
            
            // basket rows
            foreach($basket as $row){
                $thisproduct = [
                    'orderid' => $order->id,
                    'placed_by' => $order->name,
                    'customer_number' => $order->custnumber,
                    'date' => $order->created_at,
                    'qty' => number_format($row['qty'], 0),
                    'total' => number_format($row['subtotal'], 2),
                    'item' => $row['name'],
                ];
                array_push($per_product, $thisproduct);
            }
        }

        return view('reports.per_product', compact(
            'per_product'
        ));
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'per_product';
    }
}