<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['type', 'name', 'surname'];

    public function vehicles()
    {
        return $this->hasMany(CustomerVehicle::class);
    }
}
