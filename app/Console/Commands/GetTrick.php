<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetTrick extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get_trick {platform}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '拉取平台最新挂单信息';

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
      $m = 'App\Model\\'.$this->argument('platform');
      if (class_exists($m)) {
        $model = new $m;
        for ($i=1;$i<=20;$i++){
          $platform = $this->argument('platform');
          $model::getTrick();
          Log::info($platform.'拉取结束');
          sleep(3);
        }
      } else {
        Log::info($this->argument('platform').' 此Model不存在，请检查');
      }


    }
}
