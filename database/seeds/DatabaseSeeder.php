<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(CustomersVehiclesSeeder::class);
        $this->call(MechanicsSeeder::class);
        $this->call(ServicesSeeder::class);
        $this->call(OrderStatesSeeder::class);
        $this->call(OrdersSeeder::class);
    }
}
