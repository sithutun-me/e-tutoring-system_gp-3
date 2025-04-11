<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\SendInactivityEmails;
use Illuminate\Support\Facades\Schedule;


Schedule::job(new SendInactivityEmails)->weekly();  // or .daily(), depending on your needs


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('browserlog:cleanup', function () {
    $this->call(CleanupOldBrowserLogs::class);
})->daily(); // Make sure it runs daily or whenever you prefer

