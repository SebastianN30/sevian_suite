<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const STATUS_CREATED = 'creada';
    const STATUS_PENDING = 'pendiente';
    const STATUS_PARTIAL = 'pago_parcial';
    const STATUS_COMPLETED = 'completada';

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
        return $this->belongsToMany(Product::class, 'order_product')->withPivot('quantity', 'price', 'subtotal', 'internal_price', 'internal_subtotal', 'change')->withTimestamps();
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->installments->sum(function ($i) {
            return $i->amount - $i->paid_amount;
        });
    }
}
