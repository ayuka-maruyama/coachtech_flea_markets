<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryItem extends Model
{
    use HasFactory;

    protected $table = 'category_item';

    protected $fillable = ['category_id', 'item_id'];
    protected $dates = ['created_at', 'updated_at'];
}
