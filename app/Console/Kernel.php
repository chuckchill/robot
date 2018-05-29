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
<<<<<<< HEAD
        //
=======
        Commands\Inspire::class,
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
<<<<<<< HEAD
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
=======
        $schedule->command('inspire')
                 ->hourly();
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    }
}
