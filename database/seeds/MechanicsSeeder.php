<?php

use Illuminate\Database\Seeder;

class MechanicsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Mechanic::create([
            'name' => 'Piotr',
            'surname' => 'Nowak'
        ]);
    }
}
