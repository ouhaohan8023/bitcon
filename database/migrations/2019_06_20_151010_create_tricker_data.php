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
            $table->string('symbol')->comment('币种');
            $table->string('platform')->comment('平台');
            $table->decimal('bid_price', 20, 10)->comment('买方价格');
            $table->decimal('bid_qty', 40, 20)->comment('买方挂单数量');
            $table->decimal('ask_price', 20, 10)->comment('卖方价格');
            $table->decimal('ask_qty', 40, 20)->comment('卖方挂单数量');
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
