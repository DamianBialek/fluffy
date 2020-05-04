<?php

use Illuminate\Database\Seeder;

class CustomersVehiclesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Customer::find(1)->vehicles()->save(new \App\CustomerVehicle([
            'vin' => '3VWCC21C1YM455485',
            'registration_number' => 'CW2356',
            'mark' => 'Ford',
            'model' => 'Focus',
            'production_year' => '2011',
        ]));
    }
}
