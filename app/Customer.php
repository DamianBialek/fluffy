<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['type', 'company', 'name', 'surname', 'address', 'city', 'postcode', 'phone'];

    public function vehicles()
    {
        return $this->hasMany(CustomerVehicle::class);
    }

    public static function search($queryString, Builder $query = null)
    {
        if($query === null) {
            $query = static::query();
        }

        $query->orWhere("name", "LIKE", "%{$queryString}%");
        $query->orWhere("surname", "LIKE", "%{$queryString}%");

        return $query;
    }
}
