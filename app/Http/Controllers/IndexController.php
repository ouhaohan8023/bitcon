<?php

namespace App\Http\Controllers;

use App\Model\BiAn;
use App\Model\CoutData;
use App\Model\HuoBi;
use App\Model\Money;
use App\Model\TrickerData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
  public function index()
  {
//    Redis::del('trans_from_to');
    $ba = BiAn::getTrick();
    $hb = HuoBi::getTrick();
    if (Redis::get('trans_from_to')) {
      // 火币卖掉btc，Bianca买入btc
      $this->buy($ba,$hb,false);
    } else {
      // Bianca卖掉btc，火币买入btc
      $this->buy($ba,$hb,true);
    }
  }

  /**
   * @param array $ba Bianca
   * @param array $hb 火币
   * @param bool $type true 火币买，Bianca卖；false火币卖，Bianca买
   */
  public function buy($ba,$hb,$type=true)
  {
    if ($type) {
      $status = floatval($ba['bid_price']) - floatval($hb['ask_price']);// Bianca卖掉btc，火币买入btc
      if ($status > 0) {
        Log::info('可交易，Bianca卖掉btc，火币买入btc',[$status]);
        DB::transaction(function () use ($hb,$ba) {
          $bian_btc = Money::query()->select('qty')->find(1);
          Money::query()->where('id',1)->update(['price'=>$ba['bid_price'],'qty'=>0]);
          Money::query()->where('id',2)->increment('qty',$ba['bid_price']);
          $huobi_usdt = Money::query()->select('qty')->find(4);
          Money::query()->where('id',3)->update(['price'=>$hb['ask_price'],'qty'=>$huobi_usdt['qty']/$hb['ask_price']]);
          Money::query()->where('id',4)->decrement('qty',$huobi_usdt['qty']);
          Redis::set('trans_from_to',true);
          // 加入交易记录
          $c['platform_a'] = 'bian';
          $c['symbol_a'] = 'btcusdt';
          $c['price_a'] = $ba['bid_price'];
          $c['qty_a'] = $bian_btc['qty'];
          $c['fee_a'] = 0;
          $c['platform_b'] = 'huobi';
          $c['symbol_b'] = 'usdtbtc';
          $c['price_b'] = $hb['bid_price'];
          $c['qty_b'] = $huobi_usdt['qty']/$hb['ask_price'];
          $c['fee_b'] = 0;
          $c['fun'] = $c['price_a'] * $c['qty_a'] - $huobi_usdt['qty'] - $c['fee_a'] - $c['fee_b'];
          $c['status'] = 1;
          $c['msg'] = '';
          $c['platform_remark'] = '';
          CoutData::create($c);
        });
      } else {
        Log::info('不可交易1');
      }
    } else {
      $status = floatval($hb['bid_price']) - floatval($ba['ask_price']);// 火币卖掉btc，Bianca买入btc
      if ($status > 0) {
        Log::info('可交易，火币卖掉btc，Bianca买入btc',[$status]);
        DB::transaction(function () use ($hb,$ba) {
          $huobi_btc = Money::query()->select('qty')->find(3);
          Money::query()->where('id',3)->update(['price'=>$hb['bid_price'],'qty'=>0]);
          Money::query()->where('id',4)->increment('qty',$hb['bid_price']);
          $bian_usdt = Money::query()->select('qty')->find(2);
          Money::query()->where('id',1)->update(['price'=>$ba['ask_price'],'qty'=>$bian_usdt['qty']/$ba['ask_price']]);
          Money::query()->where('id',2)->decrement('qty',$bian_usdt['qty']);
          Redis::set('trans_from_to',false);
          // 加入交易记录
          $c['platform_a'] = 'huobi';
          $c['symbol_a'] = 'btcusdt';
          $c['price_a'] = $hb['bid_price'];
          $c['qty_a'] = $huobi_btc['qty'];
          $c['fee_a'] = 0;
          $c['platform_b'] = 'bian';
          $c['symbol_b'] = 'usdtbtc';
          $c['price_b'] = $ba['bid_price'];
          $c['qty_b'] = $bian_usdt['qty']/$ba['ask_price'];
          $c['fee_b'] = 0;
          $c['fun'] = $c['price_a'] * $c['qty_a'] - $bian_usdt['qty'] - $c['fee_a'] - $c['fee_b'];
          $c['status'] = 1;
          $c['msg'] = '';
          $c['platform_remark'] = '';
          CoutData::create($c);
        });
      } else {
        Log::info('不可交易2');
      }
    }
  }
}
