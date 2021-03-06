<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Customer::create([
            'type' => 'natural_person',
            'name' => 'Jan',
            'surname' => 'Kowalski',
        ]);
    }
}
