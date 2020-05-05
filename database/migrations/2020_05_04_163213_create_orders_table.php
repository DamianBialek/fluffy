<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("vehicle_id")->unsigned();
            $table->boolean("active")->default(0);
            $table->string("name", 255)->nullable();
            $table->text("note")->nullable();
            $table->dateTime("date")->nullable();
            $table->timestamps();
            $table->timestamp("finished_at")->nullable();

            $table->foreign("vehicle_id")->references("id")->on("customers_vehicles");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}