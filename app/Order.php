<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['number', 'vehicle_id', 'name', 'note', 'active', 'date', 'vehicle_mileage', 'finished_at'];

    public static function query()
    {
        $query = parent::query();
        $query->with("vehicle")->orderBy("id", "desc");

        return $query;
    }

    public static function getNumberRegex()
    {
        return "(Z)(?<number>[0-9]*)(\/".date("Y").")";
    }

    public function generateAndSetNewNumber()
    {
        $lastNumber = self::getLastNumber();

        if($lastNumber !== null) {
            $lastNumber = $lastNumber->number;

            if(preg_match("%".self::getNumberRegex()."%", $lastNumber, $matches)) {
                $number = (int)$matches['number'];
                $number += 1;
            } else {
                $number = 1;
            }
        } else {
            $number = 1;
        }

        $number = str_pad($number, 4, "0", STR_PAD_LEFT);

        $this->number = "Z{$number}/2020";
    }

    public static function getLastNumber()
    {
        return self::where("number", "REGEXP", self::getNumberRegex())->orderBy("id", "desc")->take(1)->get()->first();
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
