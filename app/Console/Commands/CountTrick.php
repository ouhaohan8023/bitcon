<?php

namespace App\Console\Commands;

use App\Model\BiAn;
use App\Model\CoutData;
use App\Model\HuoBi;
use App\Model\Money;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class CountTrick extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'count_trick';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '计算';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      for ($i=1;$i<=20;$i++){
        $ba = BiAn::getTrick();
        $hb = HuoBi::getTrick();
        if (Redis::get('trans_from_to')) {
          // 火币卖掉btc，Bianca买入btc
          $this->buy($ba,$hb,false);
        } else {
          // Bianca卖掉btc，火币买入btc
          $this->buy($ba,$hb,true);
        }
        sleep(3);
      }
    }

  /**
   * @param array $ba Bianca
   * @param array $hb 火币
   * @param bool $type true 火币买，Bianca卖；false火币卖，Bianca买
   */
  protected function buy($ba,$hb,$type=true)
  {
    $transaction_lock = Redis::get('transaction_lock');
    if ($type) {
      $bian_btc = Money::query()->select('qty')->find(1);
      $huobi_usdt = Money::query()->select('qty')->find(4);
      $status = $ba['bid_price']*$bian_btc['qty'] - $huobi_usdt['qty'];// Bianca卖掉btc，火币买入btc
      if ($status > 0 && !$transaction_lock) {
        Log::info('可交易，Bianca卖掉btc，火币买入btc',[$status]);
        // 加锁
        Redis::set('transaction_lock',true);
        DB::transaction(function () use ($hb,$ba,$bian_btc,$huobi_usdt) {
          Money::query()->where('id',1)->update(['price'=>$ba['bid_price'],'qty'=>0]);
          Money::query()->where('id',2)->increment('qty',$ba['bid_price']*$bian_btc['qty']);
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
          $c['price_b'] = $hb['ask_price'];
          $c['qty_b'] = $huobi_usdt['qty']/$hb['ask_price'];
          $c['fee_b'] = 0;
          $c['fun'] = $ba['bid_price']*$bian_btc['qty'] - $huobi_usdt['qty'] - $c['fee_a'] - $c['fee_b'];
          $c['status'] = 1;
          $c['msg'] = '';
          $c['platform_remark'] = '';
          CoutData::create($c);
        });
        Redis::set('transaction_lock',false);
      } else {
        Log::info('不可交易1');
      }
    } else {
      $huobi_btc = Money::query()->select('qty')->find(3);
      $bian_usdt = Money::query()->select('qty')->find(2);
      $status = $hb['bid_price']*$huobi_btc['qty'] - $bian_usdt['qty'];// 火币卖掉btc，Bianca买入btc
      if ($status > 0 && !$transaction_lock) {
        Log::info('可交易，火币卖掉btc，Bianca买入btc',[$status]);
        // 加锁
        Redis::set('transaction_lock',true);
        DB::transaction(function () use ($hb,$ba,$huobi_btc,$bian_usdt) {
          Money::query()->where('id',3)->update(['price'=>$hb['bid_price'],'qty'=>0]);
          Money::query()->where('id',4)->increment('qty',$hb['bid_price']*$huobi_btc['qty']);
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
          $c['price_b'] = $ba['ask_price'];
          $c['qty_b'] = $bian_usdt['qty']/$ba['ask_price'];
          $c['fee_b'] = 0;
          $c['fun'] = $hb['bid_price']*$huobi_btc['qty'] - $bian_usdt['qty'] - $c['fee_a'] - $c['fee_b'];
          $c['status'] = 1;
          $c['msg'] = '';
          $c['platform_remark'] = '';
          CoutData::create($c);
        });
        Redis::set('transaction_lock',false);
      } else {
        Log::info('不可交易2');
      }
    }
  }
}
