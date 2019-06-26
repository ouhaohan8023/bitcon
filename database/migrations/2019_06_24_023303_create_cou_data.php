<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cou_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('platform_a')->comment('A平台');
            $table->string('symbol_a')->comment('操作币种');
            $table->decimal('price_a', 20, 10)->comment('操作时的价格');
            $table->decimal('qty_a', 40, 20)->comment('操作的数量');
            $table->decimal('fee_a', 40, 20)->comment('操作手续费');
            $table->string('platform_b')->comment('B平台');
            $table->string('symbol_b')->comment('操作币种');
            $table->decimal('price_b', 20, 10)->comment('操作时的价格');
            $table->decimal('qty_b', 40, 20)->comment('操作的数量');
            $table->decimal('fee_b', 40, 20)->comment('操作手续费');
            $table->decimal('fun', 40, 20)->comment('本次操作盈利');
            $table->tinyInteger('status')->comment('0创建，1成功，2撤单，3未知状态');
            $table->string('msg')->comment('交易返回信息')->nullable($value = true);
            $table->string('platform_remark')->comment('交易后各平台币种分布状态记录，json形式');
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
        Schema::dropIfExists('cou_data');
    }
}
