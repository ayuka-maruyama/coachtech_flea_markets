<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';
    protected $primaryKey = 'item_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['item_name', 'brand', 'price', 'description', 'condition', 'item_image', 'stock_status', 'user_id'];
    protected $dates = ['created_at', 'updated_at'];

    public function favorite()
    {
        return $this->hasMany(Favorite::class, 'item_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class, 'item_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item', 'item_id', 'category_id')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
