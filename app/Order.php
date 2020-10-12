<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['number', 'vehicle_id', 'name', 'note', 'active', 'date', 'customer_company', 'customer_name', 'customer_surname', 'customer_address', 'customer_city', 'customer_postcode', 'customer_phone', 'vehicle_mileage', 'state', 'finished_at'];

    protected $appends = ['customer', 'state_name'];

    protected $hidden = ['customer_company', 'customer_name', 'customer_surname', 'customer_address', 'customer_city', 'customer_postcode', 'customer_phone', 'order_state'];

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

    public static function getMysqlNumberRegex()
    {
        return "Z[0-9]*\/".date("Y");
    }

    public static function getInvoiceNumberRegex()
    {
        return "(FV)(?<number>[0-9]*)(\/".date("Y").")";
    }

    public static function getMysqlInvoiceNumberRegex()
    {
        return "FV[0-9]*\/".date("Y");
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

        $this->number = "Z{$number}/".date("Y");;
    }

    public function generateAndSetInvoiceNumber()
    {
        $lastNumber = self::getLastInvoiceNumber();

        if($lastNumber !== null) {
            $lastNumber = $lastNumber->invoice_number;

            if(preg_match("%".self::getInvoiceNumberRegex()."%", $lastNumber, $matches)) {
                $number = (int)$matches['number'];
                $number += 1;
            } else {
                $number = 1;
            }
        } else {
            $number = 1;
        }

        $number = str_pad($number, 4, "0", STR_PAD_LEFT);

        $this->invoice_number = "FV{$number}/".date("Y");
        $this->invoice_date = Carbon::now();
    }

    public static function getLastNumber()
    {
        return self::where("number", "REGEXP", self::getMysqlNumberRegex())->orderBy("id", "desc")->take(1)->get()->first();
    }

    public static function getLastInvoiceNumber()
    {
        return self::where("invoice_number", "REGEXP", self::getMysqlInvoiceNumberRegex())->orderBy("invoice_number", "desc")->take(1)->get()->first();
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

    public function copyOrder()
    {
        $new = $this->replicate(array_diff(array_keys($this->getAttributes()), ["vehicle_id", 'name', 'note']));
        $new->generateAndSetNewNumber();

        if(!empty($new->name)) {
            $new->name .= ' - Kopia';
        }

        $new->push();

        $this->relations = [];

        $this->load("positions");

        foreach ($this->relations as $relationName => $items){
            if(method_exists($new->{$relationName}, "sync")) {
                $new->{$relationName}()->sync($items);
            } else {
                foreach($items as $item){
                    unset($item->id);
                    $new->{$relationName}()->create($item->toArray());
                }
            }
        }

        return $new;
    }

    public function getPartsPositions()
    {
        if(!$this->relationLoaded("positions")) {
            $this->load("positions");
        }

        return $this->positions->filter(function ($position) {
            return $position->type == 'part';
        });
    }

    public function getServicesPositions()
    {
        if(!$this->relationLoaded("positions")) {
            $this->load("positions");
        }

        return $this->positions->filter(function ($position) {
            return $position->type == 'service';
        });
    }

    public function getPartsTotalSum()
    {
        return $this->getPartsPositions()->sum(function ($pos) {
            return $pos->price * $pos->quantity;
        });
    }

    public function getServicesTotalSum()
    {
        return $this->getServicesPositions()->sum(function ($pos) {
            return $pos->price * $pos->quantity;
        });
    }

    public function getTotalSum()
    {
        return $this->getPartsTotalSum() + $this->getServicesTotalSum();
    }

    public function getCustomerAttribute()
    {
        return [
            'company' => $this->customer_company,
            'name' => $this->customer_name,
            'surname' => $this->customer_surname,
            'address' => $this->customer_address,
            'city' => $this->customer_city,
            'postcode' => $this->customer_postcode,
            'phone' => $this->customer_phone,
        ];
    }

    public function getStateNameAttribute()
    {
        if(!$this->relationLoaded("orderState")) {
            $this->load("orderState");
        }

        return $this->orderState->name;
    }

    public function orderState()
    {
        return $this->belongsTo(OrderState::class, 'state');
    }
}
