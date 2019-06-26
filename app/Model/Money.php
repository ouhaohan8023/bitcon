<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Money extends Model
{
    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = ['platform','symbol','price','qty'];
}
