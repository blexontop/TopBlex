<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

// Modelo del pago: guarda los datos del cobro (método, importe y la referencia de Stripe).
class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'method',
        'status',
        'reference',
        'amount',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    // Cada pago pertenece a un pedido.
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
