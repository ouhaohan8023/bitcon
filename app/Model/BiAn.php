<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BiAn extends Model
{
  /**
   * bi an
   * 最优挂单
   */
  public static function getTrick()
  {
    $url = "https://api.binance.com";
    $path = "/api/v3/ticker/bookTicker?";//最优挂单接口
    $data['symbol'] = "BTCUSDT";
    $u = $url . $path . http_build_query($data);
    $res = _httpGet($u);
    if ($res['code'] == 200) {
      $r = json_decode($res['data'], true);
      $data['platform'] = 'bian';
      $data['bid_price'] = $r['bidPrice'];//最高买价
      $data['bid_qty'] = $r['bidQty'];
      $data['ask_price'] = $r['askPrice'];//最低卖价
      $data['ask_qty'] = $r['askQty'];
//      TrickerData::create($data);
      return $data;
    } else {
      Log::info('httpError:BIAN', $res);
      return false;
    }
  }
}
