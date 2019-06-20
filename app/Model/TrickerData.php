<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TrickerData extends Model
{
    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = ['symbol','platform','bid_price','bid_qty','ask_price','ask_qty'];
}
