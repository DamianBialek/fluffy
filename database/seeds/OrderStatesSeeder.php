<?php

use Illuminate\Database\Seeder;

class OrderStatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\OrderState::insert([
            ['name' => 'nowe'],
            ['name' => 'w realizacji'],
            ['name' => 'zrealizowane'],
        ]);
    }
}
