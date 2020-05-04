<?php

use Illuminate\Database\Seeder;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\CustomerVehicle::find(1)->orders()->save(new \App\Order());
        \App\Order::find(1)->services()->attach(\App\Service::find(1));
    }
}
