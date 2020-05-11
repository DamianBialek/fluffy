<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers_vehicles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("customer_id")->unsigned();
            $table->string("vin")->unique();
            $table->string("registration_number", 64);
            $table->string("mark", 45);
            $table->string("model", 45);
            $table->string("production_year", 4);
            $table->timestamps();

            $table->foreign("customer_id")->references("id")->on("customers");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers_vehicles');
    }
}
