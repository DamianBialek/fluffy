<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function vehicle()
    {
        return $this->belongsTo(CustomerVehicle::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'orders_services');
    }
}
