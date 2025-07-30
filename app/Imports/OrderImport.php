<?php

namespace App\Imports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;

class OrderImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Order([
            'id' => (string) Str::uuid(),
            'product_name' => $row[0],
            'product_price' => $row[1],
            'quantity' => $row[2],
            'total_price' => $row[3],
        ]);
    }
}

