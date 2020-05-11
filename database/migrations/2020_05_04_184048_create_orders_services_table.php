<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_services', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("order_id")->unsigned();
            $table->string("name", 255);
            $table->decimal("price_net", 10, 2);
            $table->integer("quantity")->default(1);
            $table->timestamps();

            $table->foreign("order_id")->references("id")->on("orders")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_services');
    }
}
