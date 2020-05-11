<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerVehicle extends Model
{
    public $table = 'customers_vehicles';

    protected $fillable = ['customer_id', 'vin', 'registration_number', 'mark', 'model', 'production_year'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'vehicle_id');
    }
}
