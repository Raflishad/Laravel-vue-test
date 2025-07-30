<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Product extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'category_id', 'name', 'description', 'price',
        'is_active', 'tags', 'release_date'
    ];

    public $incrementing = false;
    protected $keyType = 'string';
    protected $casts = [
        'is_active' => 'boolean',
        'tags' => 'array',
        'release_date' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($product) {
            $product->id = (string) Str::uuid();
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

