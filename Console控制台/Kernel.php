<?php

namespace App\Console;

use App\Http\Business\MessageLogBusiness;
use App\Http\Business\MessagesBusiness;
use App\Http\Controllers\Cron\MessageController;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
//        $schedule->call(function () {

//        })->everyMinute();

        $schedule->command('msg:push-msg')->everyMinute();
    }
    
}
