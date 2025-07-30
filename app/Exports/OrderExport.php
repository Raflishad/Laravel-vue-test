<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Order::select(['product_name', 'product_price', 'quantity', 'total_price'])->get();
    }

    public function headings(): array
    {
        return ['product_name', 'product_price', 'quantity', 'total_price'];
    }
}
