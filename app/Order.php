<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
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

    public static function search($queryString, Builder $query = null)
    {
        if($query === null) {
            $query = static::query();
        }

        $query->orWhere("name", "LIKE", "%{$queryString}%");
        $query->orWhereHas("vehicle", function ($q) use($queryString) {
            $q->where(function ($q) use($queryString) {
                $q->orWhere("registration_number", "LIKE", "%{$queryString}%");
                $q->orWhere("vin", "LIKE", "%{$queryString}%");
                $q->orWhere("mark", "LIKE", "%{$queryString}%");
                $q->orWhere("model", "LIKE", "%{$queryString}%");
            });
        });


        return $query;
    }

    public function vehicle()
    {
        return $this->belongsTo(CustomerVehicle::class);
    }

    public function positions()
    {
        return $this->hasMany(OrderPosition::class, 'order_id', 'id');
    }
}
