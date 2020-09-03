<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderPosition extends Model
{
    public $table = 'orders_positions';

    protected $fillable = ['type', 'name', 'price', 'quantity'];
}
