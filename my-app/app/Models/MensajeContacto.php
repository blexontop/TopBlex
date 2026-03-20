<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class MensajeContacto extends Model
{
    protected $fillable = [
        'user_id',
        'nombre',
        'email',
        'asunto',
        'mensaje',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
