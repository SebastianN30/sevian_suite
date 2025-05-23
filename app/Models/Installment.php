<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    /** @use HasFactory<\Database\Factories\InstallmentFactory> */
    use HasFactory;

    const STATUS_PENDING = 'pendiente';
    const STATUS_PAYED = 'pagada';
    const STATUS_EXPIRED = 'vencida';

    protected $fillable = [
        'order_id',
        'amount',
        'due_date',
        'paid_amount',
        'paid_at',
        'status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
