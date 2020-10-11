<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    protected $fillable = ['name', 'value'];

    public static function getByName($name)
    {
        return self::where("name", $name)->first();
    }
}
