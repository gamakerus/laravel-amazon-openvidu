<?php

namespace App\Console;

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
        Commands\IntatimeCron::class,
        Commands\IntnotificationCron::class,
        Commands\JobfinishedCron::class,
        Commands\PaycycleCron::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('intnotification:cron')->timezone('America/Chicago')->everyFiveMinutes();
        // $schedule->command('intatime:cron')->timezone('America/Chicago')->hourly();
        // $schedule->command('jobfinished:cron')->timezone('America/Chicago')->daily();
        // $schedule->command('paycycle:cron')->timezone('America/Chicago')->daily();
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
