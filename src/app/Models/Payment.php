<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['order_id', 'stripe_payment_id', 'stripe_customer_id', 'payment_status'];
    protected $dates = ['created_at', 'updated_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
