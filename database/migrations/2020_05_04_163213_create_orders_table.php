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
            $table->bigInteger("vehicle_id")->unsigned()->nullable();
            $table->boolean("active")->default(0);
            $table->string("number")->unique();
            $table->string("name", 255)->nullable();
            $table->text("note")->nullable();
            $table->dateTime("date")->nullable();
            $table->string("customer_company")->nullable();
            $table->string("customer_name")->nullable();
            $table->string("customer_surname")->nullable();
            $table->string("customer_address")->nullable();
            $table->string("customer_city")->nullable();
            $table->string("customer_postcode")->nullable();
            $table->string("customer_phone")->nullable();
            $table->integer("vehicle_mileage")->nullable();
            $table->string("invoice_number")->nullable()->unique();
            $table->timestamp("invoice_date")->nullable();
            $table->timestamps();
            $table->timestamp("finished_at")->nullable();

            $table->foreign("vehicle_id")->references("id")->on("customers_vehicles")->onDelete("set null");
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
