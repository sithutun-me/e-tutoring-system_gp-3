<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Mail\StudentInactivityMail;
use App\Mail\TutorInactivityAlertMail;
use Illuminate\Support\Facades\Mail;


class SendInactivityEmails implements ShouldQueue
{
    use  Queueable;

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
    public function handle()
    {
        \Log::info('Running inactivity check at ' . now());
    
        try {
            $minDays = 28;
            $startDate = Carbon::now()->subDays($minDays);
            $endDate = null;
    
            \Log::info('Running inactivity check at ' . now());
    
            // Get inactive students
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
    
            \Log::info('Inactive students found: ' . $inactiveStudents->count());
    
            // Loop through each inactive student and send an email
            foreach ($inactiveStudents as $student) {
                \Log::info("Sending email to student: {$student->email}");
                Mail::to($student->email)->send(new StudentInactivityMail($student));
    
                $tutorId = DB::table('allocation')
                            ->where('student_id', $student->id)
                            ->value('tutor_id');
                $tutorName = DB::table('users')
                            ->where('id', $tutorId)
                            ->selectRaw('CONCAT(first_name, " ", last_name) as full_name')
                            ->value('full_name');
                $tutorEmail = DB::table('users')
                            ->where('id', $tutorId)
                            ->value('email');
                if($tutorId){
                    Mail::to($tutorEmail)->send(new TutorInactivityAlertMail($tutorName, $student));
                }
                \Log::info("Sending email to student:"  . $tutorId . $tutorName . $tutorEmail);
               
                // // If the student has a tutor, notify the tutor
                // if ($student->tutor) {
                //     \Log::info("Notifying tutor: {$student->tutor->email}");
                //     try {
                //         Mail::to($student->tutor->email)->send(new TutorInactivityAlertMail($student->tutor, $student));
                //         \Log::info("Email sent to tutor: {$student->tutor->email}");
                //     } catch (\Exception $e) {
                //         \Log::error('Error sending email to tutor: ' . $student->tutor->email . ' - ' . $e->getMessage());
                //     }
                // }
            }
        } catch (\Exception $e) {
            \Log::error('Error in SendInactivityEmails job: ' . $e->getMessage());
        }
    
    }
}
