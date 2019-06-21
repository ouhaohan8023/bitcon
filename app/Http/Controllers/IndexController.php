<?php

namespace App\Http\Controllers;

use App\Model\BiAn;
use App\Model\HuoBi;
use App\Model\TrickerData;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller
{
  public function index()
  {
    BiAn::getTrick();
    HuoBi::getTrick();
  }
}
