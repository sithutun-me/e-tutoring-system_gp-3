<?php

namespace App\Console\Commands;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\User;
use Carbon\Carbon;

use App\Mail\StudentInactivityMail;
use App\Mail\TutorInactivityAlertMail;
use Illuminate\Support\Facades\Mail;


class SendInactivityEmails implements Command
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $minDays = 28;
        $startDate = Carbon::now()->subDays($minDays);
        $endDate = null;

        Log::info('Running inactivity check at ' . now());

        $inactiveStudents = User::where('role_id', 1)
            ->whereDoesntHave('posts', function ($query) use ($startDate, $endDate) {
                $query->where('updated_at', '>=', $startDate);
                if ($endDate) {
                    $query->where('updated_at', '<', $endDate);
                }
            })
            ->whereDoesntHave('comments', function ($query) use ($startDate, $endDate) {
                $query->where('updated_at', '>=', $startDate);
                if ($endDate) {
                    $query->where('updated_at', '<', $endDate);
                }
            })
            ->get();

        Log::info('Inactive students found: ' . $inactiveStudents->count());

        foreach ($inactiveStudents as $student) {
            Log::info("Sending email to student: {$student->email}");

            Mail::to($student->email)->send(new StudentInactivityMail($student));

            if ($student->tutor) {
                Log::info("Notifying tutor: {$student->tutor->email}");
                Mail::to($student->tutor->email)->send(new TutorInactivityAlertMail($student->tutor, $student));
            }
        }
    }
}
