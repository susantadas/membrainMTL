<?php
namespace App\Console;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\Leads::class,
        Commands\Dncr::class,
        Commands\RapportPhone::class,
        Commands\Phonehistory::class,
        Commands\Emailhistory::class,
        Commands\Fraudtable::class,
        Commands\FraudDetections::class,
        Commands\DownloadCSV::class,
        Commands\DownloadCSVfromsftp::class,
        Commands\UploadCSVfromsftp::class,
        Commands\DownloadCSVQuarantine::class,
    ];

    protected function schedule(Schedule $schedule) {
        $schedule->command('leads:clear')->daily();
        $schedule->command('dncr:clear')->daily();
        $schedule->command('rapportphone:clear')->daily();
        $schedule->command('phonehistory:clear')->daily();
        $schedule->command('emailhistory:clear')->daily();
        $schedule->command('frauddetections:clear')->hourly();
        $schedule->command('fraudtable:clear')->hourly();
        $schedule->command('downloadcsvquarantine:download')->cron('*/15 * * * * *')->withoutOverlapping();
        $schedule->command('downloadcsvfromsftp:download')->cron('*/10 * * * * *')->withoutOverlapping();
        $schedule->command('uploadcsvfromsftp:upload')->cron('*/10 * * * * *')->withoutOverlapping();
        $schedule->command('downloadcsv:download')->cron('*/15 * * * * *')->withoutOverlapping();
    }

    protected function commands() {
        require base_path('routes/console.php');
    }
}