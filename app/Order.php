<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['vehicle_id', 'name', 'note', 'active', 'date', 'finished_at'];

    public static function query()
    {
        $query = parent::query();
        $query->with("vehicle");

        return $query;
    }

    public function vehicle()
    {
        return $this->belongsTo(CustomerVehicle::class);
    }

    public function services()
    {
        return $this->hasMany(OrderService::class, 'order_id', 'id');
    }
}
