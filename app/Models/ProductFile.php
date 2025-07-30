<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;

class ProductFile extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['product_id', 'file_path'];
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        static::creating(function ($file) {
            $file->id = (string) Str::uuid();
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function files()
    {
        return $this->hasMany(ProductFile::class);
    }
}
