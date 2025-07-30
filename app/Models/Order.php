<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Order extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'product_id', 'product_name', 'product_price',
        'quantity', 'total_price',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            $order->id = (string) Str::uuid();
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
