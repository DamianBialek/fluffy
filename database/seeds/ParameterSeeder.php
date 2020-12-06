<?php

use Illuminate\Database\Seeder;

class ParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Parameter::create([
            "name" => "tax",
            "value" => 23
        ]);
    }
}
