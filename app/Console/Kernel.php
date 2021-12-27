<?php

namespace App\Console;

use App\Election;
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
        Commands\ElectionStart::class,
        Commands\ElectionEnd::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $elections = Election::all();
        if(count($elections) > 0)
        {
            foreach($elections as $election)
            {
                $schedule->command('election:start',[$election->id])->everyMinute();
                $schedule->command('election:end',[$election->id])->everyMinute();
            }
        }
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
