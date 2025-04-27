<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = '1';
    const STATUS_INACTIVE = '0';

    protected $fillable = [
        'image',
        'name',
        'stock',
        'note',
        'internal_price',
        'profit_percentage',
        'sale_price',
        'status'
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')->withPivot('quantity', 'price', 'subtotal', 'internal_price', 'internal_subtotal', 'change')->withTimestamps();
    }
}
