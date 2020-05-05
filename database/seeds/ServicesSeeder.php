<?php

use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Service::create([
            'name' => 'Wymiana oleju',
            'price_net' => '50'
        ]);
    }
}