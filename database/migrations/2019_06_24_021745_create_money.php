<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoney extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('platform')->comment('平台');
            $table->string('symbol')->comment('币种');
            $table->decimal('price', 20, 10)->comment('最近一笔价格');
            $table->decimal('qty', 40, 20)->comment('现存数量');
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
        Schema::dropIfExists('money');
    }
}
