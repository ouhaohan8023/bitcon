<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class HuoBi extends Model
{
  /**
   * bi an
   * 最优挂单
   */
  public static function getTrick()
  {
    $url = 'https://api.huobi.pro';
    $path = "/market/depth?";//最优挂单接口
    $u = $url . $path;
    $data['symbol'] = "btcusdt";
    $data['depth'] = "10";
    $data['type'] = "step0";
    $u = $url . $path . http_build_query($data);
    $res = _httpGet($u);
    if ($res['code'] == 200) {
      $r = json_decode($res['data'], true);
      if (isset($r['tick'])) {
        $data['symbol'] = "btcusdt";
        $data['platform'] = 'huobi';
        $data['bid_price'] = $r['tick']['bids'][0][0];
        $data['bid_qty'] = $r['tick']['bids'][0][1];
        $data['ask_price'] = $r['tick']['asks'][0][0];
        $data['ask_qty'] = $r['tick']['asks'][0][1];
        TrickerData::create($data);
        return $data;
      } else{
        Log::info('httpError:HUOBI', $res);
        return false;
      }
    } else {
      Log::info('httpError:HUOBI', $res);
      return false;
    }
  }
}
