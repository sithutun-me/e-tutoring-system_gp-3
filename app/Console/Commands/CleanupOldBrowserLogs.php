<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BrowserUsage;
use Carbon\Carbon;


class CleanupOldBrowserLogs extends Command
{
    
    protected $signature = 'app:cleanup-old-browser-logs';
    protected $description = 'Delete browser logs older than 1 month';

    public function handle()
    {
        $deleted = BrowserLog::where('created_at', '<', Carbon::now()->subMonth())->delete();
        $this->info("Deleted {$deleted} old browser logs.");


        //alternative for cleaning up month by month 

        // // Get the first day of the current month, then subtract one month to get the last month.
        // $previousMonth = Carbon::now()->subMonth()->startOfMonth();
        // // Delete all browser logs before the first day of the previous month.
        // $deleted = BrowserLog::where('created_at', '<', $previousMonth)->delete();
        
        // $this->info("Deleted {$deleted} old browser logs from before {$previousMonth->toDateString()}.");
        
    }
}
