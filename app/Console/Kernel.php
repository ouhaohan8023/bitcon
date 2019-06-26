<?php

namespace App\Console;

use App\Console\Commands\Alert100;
use App\Console\Commands\ClearDatabase;
use App\Console\Commands\GetTrick;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GetTrick::class,
        ClearDatabase::class,
        Alert100::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//      $schedule->command('get_trick BiAn')->everyMinute();
//      $schedule->command('get_trick HuoBi')->everyMinute();
//      $schedule->command('clear_database')->everyMinute();
//      $schedule->command('count_trick')->everyMinute();
      $schedule->command('alert100')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
