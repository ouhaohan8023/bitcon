<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CoutData extends Model
{
    protected $table = 'cou_data';
    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = ['platform_a','symbol_a','price_a','qty_a','fee_a',
        'platform_b','symbol_b','price_b','qty_b','fee_b',
        'fun','status','msg','platform_remark'];
}
