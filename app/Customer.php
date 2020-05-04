<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function vehicles()
    {
        return $this->hasMany(CustomerVehicle::class);
    }
}
