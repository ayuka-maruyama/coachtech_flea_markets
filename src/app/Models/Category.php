<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'category_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['category_name'];
    protected $dates = ['created_at', 'updated_at'];

    public function item()
    {
        return $this->belongsToMany(Item::class, 'category_item', 'category_id', 'item_id')->withTimestamps();
    }
}
