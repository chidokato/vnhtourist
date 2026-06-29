<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'post_id',
        'tour_name',
        'adult_quantity',
        'child_quantity',
        'infant_quantity',
        'price',
        'total',
        'departure_date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
