<?php

namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

use App\Order;

class PerOrderSheet implements FromView, WithTitle
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

        $per_order = [];
        foreach($orders as $order){
            // basket & basket item list
            $basket = json_decode($order->basket, true);
            
            // sheet: per order
            $total = 0; // total to add on to
            $items = ''; // items to append to
            
            // basket rows
            foreach($basket as $row){
                $items = $items . ', ' . $row['name'];
                $total = $total + $row['subtotal'];
            }
            
            // build the order row and push onto the array
            $thisorder = [
                'orderid' => $order->id,
                'placed_by' => $order->name,
                'customer_number' => $order->custnumber,
                'date' => $order->created_at,
                'total' => number_format($total, 2),
                'items' => substr($items, 2), // remove leading comma
            ];
            array_push($per_order, $thisorder);
        }

        return view('reports.per_order', compact(
            'per_order'
        ));
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'per_order';
    }
}