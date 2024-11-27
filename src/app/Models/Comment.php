<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';
    protected $fillable = ['comment_id', 'profile_id', 'item_id', 'comment'];
    protected $dates = ['created_at', 'updated_at'];
}
