<?php

namespace App\Console\Commands;

use App\Model\TrickerData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ClearDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear_database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清除十分钟之前的所有挂单数据';

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
      $time = date('H:i:s',time()-10*60);
      TrickerData::query()->whereTime('created_at', '<', $time)->delete();
      Log::info('清理成功');
    }
}
