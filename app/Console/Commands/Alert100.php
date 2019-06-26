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
    protected $description = '波动超过100usdt，进行telegram通知';

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
        $hb = BiAn::getTrick();
        $last = Redis::get('last_btc_usdt');
        if (!$last) {
          $last = 0;
        }
        if (abs($hb['ask_price'] - $last) >= 100) {
          Redis::set('last_btc_usdt',$hb['ask_price']);
          $tgMsg = '波动超过100，现价：'.$hb['ask_price'].'，原价：'.$last;
          $method = 'sendMessage';
          $backMsg['chat_id'] = env('TELEGRAM_GROUP');
          $backMsg['text'] = $tgMsg;
          $backMsg['parse_mode'] = 'Markdown';
          telegramFunction($method, $backMsg);
        }
        sleep(11);
      }
    }
}
