<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pendiente';
    const STATUS_COMPLETED = 'completado';
    const STATUS_OVERDUE = 'atrasado';

    protected $fillable = [
        'order_id',
        'amount',
        'payment_date',
        'payment_method',
        'note',
        'status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
