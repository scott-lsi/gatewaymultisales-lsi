<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use App\Exports\Sheets\PerProductSheet;
use App\Exports\Sheets\PerOrderSheet;

class OrderExport implements WithMultipleSheets
{
    use Exportable;

    protected $monthago_formatted;
    protected $today_formatted;
    
    public function __construct(string $monthago_formatted, string $today_formatted)
    {
        $this->monthago_formatted = $monthago_formatted;
        $this->today_formatted = $today_formatted;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new PerOrderSheet($this->monthago_formatted, $this->today_formatted);
        $sheets[] = new PerProductSheet($this->monthago_formatted, $this->today_formatted);

        return $sheets;
    }
}
