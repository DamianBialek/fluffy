<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerVehicle extends Model
{
    public $table = 'customers_vehicles';

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'vehicle_id');
    }
}
