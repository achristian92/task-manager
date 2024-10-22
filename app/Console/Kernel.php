<?php

namespace App\Console;

use App\Console\Commands\ActivitiesDeadline;
use App\Console\Commands\ActivitiesNotFinished;
use App\Console\Commands\ActivityEvaluation;
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
        ActivitiesDeadline::class,
        ActivitiesNotFinished::class,
        ActivityEvaluation::class,
    ];


    protected function schedule(Schedule $schedule)
    {
        $schedule->command('deadline:activities')
            ->dailyAt('18:00')
            ->emailOutputOnFailure('alan.ruiz@brainbox.pe');

        $schedule->command('notfinished:activities')
            ->dailyAt('07:00')
            ->emailOutputOnFailure('alan.ruiz@brainbox.pe');

        $schedule->command('evaluation:activities')
            ->dailyAt('07:30')
            ->emailOutputOnFailure('alan.ruiz@brainbox.pe');

        $schedule->command('limithours:customer')
            ->dailyAt('16:30')
            ->emailOutputOnFailure('alan.ruiz@brainbox.pe');
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
