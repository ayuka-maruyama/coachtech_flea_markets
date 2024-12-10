<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';
    protected $fillable = ['item_name', 'brand', 'price', 'description', 'condition', 'item_image', 'stock_status', 'user_id'];
    protected $dates = ['created_at', 'updated_at'];

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item', 'item_id', 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
