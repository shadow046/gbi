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
        //
        // 'App\Console\Commands\EmailNotification'
        Commands\EmailNotification::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('remark:history')->everyTwoMinutes()->after(function () {
            exec('cd /var/www/html/mssql1/ && php artisan email:ticket');
            // $schedule->command('remark:history')->everyMinute()->withoutOverlapping();
        });
        // $schedule->command('email:ticket')->everyMinute()->withoutOverlapping();
        // $schedule->command('remark:history')->everyMinute()->withoutOverlapping();
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
