<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'productos';

    protected $fillable = [
        'name',
        'description',
        'creator_user_id',
        'category',
        'price',
        'stock',
        'img_url',
        'color',
        'make',
        'model',
        'availability',
        'keywords',
        'deleted'
    ];
}
