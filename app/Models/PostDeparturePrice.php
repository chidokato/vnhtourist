<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostDeparturePrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'departure_date',
        'price',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
