<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'image',
        'name',
        'hb',
        'hj',
        'stok',
        'user_id',
        'category_id',
    ];

    public function user()
    {
        return  $this->belongsTo(User::class);
    }
    public function category()
    {
        return  $this->belongsTo(Category::class);
    }
}
