<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const STATUS_COMPLETED = 'completada';
    const STATUS_PENDING = 'pendiente';
    const STATUS_CREATED = 'creada';
    const STATUS_CANCELLED = 'cancelada';

    protected $fillable = [
        'client_id',
        'status',
        'total',
        'internal_total'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')->withPivot('quantity', 'price', 'subtotal', 'internal_price', 'internal_subtotal')->withTimestamps();
    }
}
