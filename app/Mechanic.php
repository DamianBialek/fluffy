<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    protected $fillable = ['name', 'surname'];

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
