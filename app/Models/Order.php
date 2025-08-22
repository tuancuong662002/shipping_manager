<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['order_id', 'seller', 'status', 'address' , 'sellerPhone', 'webhook_url' , 'updated_at' , 'created_at'];
}