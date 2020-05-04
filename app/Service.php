<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'orders_services');
    }
}
