<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrickerData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tricker_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('symbol');
            $table->string('platform');
            $table->decimal('bid_price', 20, 10);
            $table->decimal('bid_qty', 40, 20);
            $table->decimal('ask_price', 20, 10);
            $table->decimal('ask_qty', 40, 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tricker_data');
    }
}
