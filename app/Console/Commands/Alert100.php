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

class Alert100 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert100';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '检测卖价是否超过成本价';

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
      for ($i=1;$i<=5;$i++){
//        $hb = BiAn::getTrick();
//        $last = Redis::get('last_btc_usdt');
//        if (!$last) {
//          $last = 0;
//        }
//        Log::info('log',[$hb['ask_price'],$last]);
//        if (abs($hb['ask_price'] - $last) >= 100) {
//          Redis::set('last_btc_usdt',$hb['ask_price']);
//          $tgMsg = '波动超过100，现价：'.$hb['ask_price'].'，原价：'.$last;
//          $method = 'sendMessage';
//          $backMsg['chat_id'] = env('TELEGRAM_GROUP');
//          $backMsg['text'] = $tgMsg;
////          $backMsg['parse_mode'] = 'Markdown';
//          telegramFunction($method, $backMsg);
//          $this->check();
//        }
//        sleep(11);
        $this->checkSell();
        sleep(11);
      }
    }

    protected function checkSell () {
      $sell = json_decode($this->checkRequest(1),true);
      $sell_price = $sell['data'][0]['match']['amount'];
      if (isset($sell['data'][0]['match']['price']) && intval($sell['data'][0]['match']['price']) >= intval(env('BUY_PRICE'))) {
        $tgMsg = env('CHECK_HUOBI_BTC_QTY').' BTC 当前卖出价格：'.$sell_price;
        $method = 'sendMessage';
        $backMsg['chat_id'] = env('TELEGRAM_GROUP');
        $backMsg['text'] = $tgMsg;
//      $backMsg['parse_mode'] = 'Html';
        telegramFunction($method, $backMsg);
      }
    }

    protected function checkBuy () {
      $buy = json_decode($this->checkRequest(0),true);
      $buy_price = $buy['data'][0]['match']['amount'];
      if (intval($buy['data'][0]['match']['price']) >= 91888) {
        $tgMsg = env('CHECK_HUOBI_BTC_QTY').' BTC 当前买入价格：'.$buy_price;
        $method = 'sendMessage';
        $backMsg['chat_id'] = env('TELEGRAM_GROUP');
        $backMsg['text'] = $tgMsg;
//      $backMsg['parse_mode'] = 'Html';
        telegramFunction($method, $backMsg);
      }
    }

    protected function checkRequest ($matchType = 0) {
      $url = env('CHECK_HUOBI_URL');
      $data['coinId'] = 1;
      $data['currencyId'] = 1;
      $data['matchType'] = $matchType;//0买币，1卖币
      $data['quantity'] = env('CHECK_HUOBI_BTC_QTY');
      $r = curlGet($url,'post',$data);
      return $r;
    }
}
