<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'pedidos';

    protected $fillable = [
        'pedido_id',
        'producto_id',
        'user_id',
        'amount',
        'completed',
        'cancelled',
        'wishlist',
    ];
        
}
