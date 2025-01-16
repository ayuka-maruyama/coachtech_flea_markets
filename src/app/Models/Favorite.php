<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favorite extends Model
{
    use HasFactory;

    protected $table = 'favorites';
    protected $primaryKey = 'favorite_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['item_id', 'user_id'];
    protected $dates = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
