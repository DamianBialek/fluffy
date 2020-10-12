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
        $order = new \App\Order();
        $order->generateAndSetNewNumber();

        \App\CustomerVehicle::find(1)->orders()->save($order);
        \App\Order::find(1)->positions()->save(new \App\OrderPosition(['name' => 'test', 'price' => 25]));
    }
}
