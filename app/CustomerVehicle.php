<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
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

    public static function search($queryString, Builder $query = null)
    {
        if($query === null) {
            $query = static::query();
        }

        $query->orWhere("vin", "LIKE", "%{$queryString}%");
        $query->orWhere("registration_number", "LIKE", "%{$queryString}%");
        $query->orWhere("mark", "LIKE", "%{$queryString}%");
        $query->orWhere("model", "LIKE", "%{$queryString}%");
        $query->orWhere("production_year", "LIKE", "%{$queryString}%");

        return $query;
    }
}
