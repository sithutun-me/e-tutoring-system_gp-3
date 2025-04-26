<?php

namespace App\Services;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Allocation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\PageView;

class AdminService
{
    /**
     * Get the number of students inactive for the given time period.
     *
     * @param int $days
     * @return int
     */
    public function getBrowserPieData()
    {
        $browserStats = DB::table('browser_logs')
            ->select('browser', DB::raw('count(*) as count'))
            ->groupBy('browser')
            ->orderByDesc('count')
            ->limit(3)
            ->get();
        return $browserStats;
    }

    public function getMostViewPage()
    {
        $pageViews = PageView::orderBy('view_count', 'desc')->get()->first();
        return $pageViews;
    }
    public function getInactiveStudentsCount(int $minDays, ?int $maxDays = null)
    {
        // $startDate = Carbon::now()->subDays($minDays);
        // $endDate = $maxDays ? Carbon::now()->subDays($maxDays) : null;

        // // $startDate = now();
        // // $endDate = null;
        // // // $cutoffStart = now()->subDays(30);  // More than 7 days ago (upper bound: 30)
        // // // $cutoffEnd = now()->subDays(7);     //

        // // $startDate = $maxDays ?  now()->subDays($maxDays):null;
        // // $endDate = now()->subDays($minDays);

        // $inactiveStudents = User::where('role_id', 1)
        //     ->whereDoesntHave('posts', function ($query) use ($startDate, $endDate) {
        //         $query->where('updated_at', '>=', $startDate);
        //         if ($endDate) {
        //             $query->where('updated_at', '<', $endDate);
        //         }
        //     })
        //     ->whereDoesntHave('comments', function ($query) use ($startDate, $endDate) {
        //         $query->where('updated_at', '>=', $startDate);
        //         if ($endDate) {
        //             $query->where('updated_at', '<', $endDate);
        //         }
        //     })

        //     ->count();

        // return $inactiveStudents;
        $olderThan = now()->subDays($minDays);   // >7 days inactive
        $newerThan = now()->subDays($maxDays); 
        // if ($noInteractionPeriod === '7days') {
        //     $olderThan = now()->subDays(7);   // >7 days inactive
        //     $newerThan = now()->subDays(30);  // ≤30 days inactive
        // } elseif ($noInteractionPeriod === '30days') {
        //     $olderThan = now()->subDays(30);  // >30 days inactive
        //     $newerThan = now()->subDays(60);  // ≤60 days inactive
        // } elseif ($noInteractionPeriod === '60days') {
        //     $olderThan = now()->subDays(60);  // >60 days inactive
        //     $newerThan = null;                // No upper limit
        // }
    
        $query = User::where('role_id', 1)
                    ->leftJoin('post', 'post.post_create_by', '=', 'users.id')
                    ->leftJoin('comment', 'comment.user_id', '=', 'users.id')
                    ->select([
                        'users.id',
                        'users.user_code',
                    'users.first_name',
                    'users.last_name',
                    'users.email',
                    DB::raw('GREATEST(
                        COALESCE(MAX(post.updated_at), "1970-01-01"),
                        COALESCE(MAX(comment.updated_at), "1970-01-01"),
                        COALESCE(users.updated_at, "1970-01-01")
                    ) as last_active_date'),
                    DB::raw('CASE 
        WHEN GREATEST(
            COALESCE(MAX(post.updated_at), "1970-01-01"),
            COALESCE(MAX(comment.updated_at), "1970-01-01"),
            COALESCE(users.updated_at, "1970-01-01")
        ) = "1970-01-01" 
        THEN DATEDIFF(NOW(), "1970-01-01") 
        ELSE DATEDIFF(NOW(), GREATEST(
            COALESCE(MAX(post.updated_at), "1970-01-01"),
            COALESCE(MAX(comment.updated_at), "1970-01-01"),
            COALESCE(users.updated_at, "1970-01-01")
        )) 
    END as no_interaction_days')
                    ])
                   
                    ->groupBy('users.id', 'user_code', 'first_name', 'last_name', 'email', 'users.updated_at');
                  
        if ($minDays === 60) {
            $query->havingRaw('no_interaction_days > 60');
        } 
        
        else {
            $query->havingRaw('no_interaction_days > ?', [$olderThan->diffInDays(now())])
                  ->havingRaw('no_interaction_days <= ?', [$newerThan->diffInDays(now())]);
        }
         $query->where(function($q) use ($olderThan) {
            $q->whereDoesntHave('posts', function($q2) use ($olderThan) {
                  $q2->where('updated_at', '>', $olderThan);
               })
               ->orWhereDoesntHave('comments', function($q2) use ($olderThan) {
                  $q2->where('updated_at', '>', $olderThan);
               });
        });
        $students = $query->count();
              
    
        return $students;
    }

    /**
     * Get inactive students data for 7, 30, and 60 days.
     *
     * @return array
     */
    public function getInactiveStudentsData()
    {
        return [
            'inactive_7_days' => $this->getInactiveStudentsCount(7, 30),
            'inactive_30_days' => $this->getInactiveStudentsCount(30, 60),
            'inactive_60_days' => $this->getInactiveStudentsCount(60),
        ];
    }


    public function getAverageMessagesPerTutor(int $timeFrame)
    {
        // $messages = DB::table('users AS tutors')
        //     ->select(
        //         'tutors.first_name AS tutor_name',
        //         DB::raw('COUNT(comment.id) AS message_count')
        //     )
        //     ->leftJoin('post', function ($join) {
        //         $join->on('post.post_create_by', '=', 'tutors.id')
        //             ->orOn('post.post_received_by', '=', 'tutors.id');
        //     })
        //     ->leftJoin('comment', function ($join) use ($timeFrame) {
        //         $join->on('comment.post_id', '=', 'post.id')
        //             ->whereIn('comment.user_id', function ($query) {
        //                 $query->select('id')
        //                     ->from('users')
        //                     ->where('role_id', 1); // Only students
        //             })
        //             ->where('comment.created_at', '>=', now()->subMonths($timeFrame));
        //     })
        //     ->where('tutors.role_id', 2) // Only tutors
        //     ->groupBy('tutors.id', 'tutors.first_name')
        //     ->get();
        $startOfMonth = now()->startOfMonth();
$daysSoFar = now()->diffInDays($startOfMonth) + 1; // +1 to include today

$messages = DB::table('users AS tutors')
    ->select(
        'tutors.first_name AS tutor_name',
        DB::raw('ROUND(COUNT(comment.id) / DATEDIFF(NOW(), \'' . $startOfMonth->toDateString() . '\'), 2) as avg_messages_per_day')
        //DB::raw('ROUND(COUNT(DISTINCT comment.id) / ' . $daysSoFar . ', 2) AS average_messages_per_day')
    )
    ->leftJoin('post', function($join) {
        $join->on('post.post_create_by', '=', 'tutors.id')
             ->orOn('post.post_received_by', '=', 'tutors.id');
    })
    ->leftJoin('comment', function($join) use ($startOfMonth) {
        $join->on('comment.post_id', '=', 'post.id')
             ->where('comment.updated_at', '>=', $startOfMonth)
             ->whereIn('comment.user_id', function($query) {
                 $query->select('id')
                       ->from('users')
                       ->where('role_id', 1); // Only students
             });
    })
    ->where('tutors.role_id', 2) // Only tutors
    ->groupBy('tutors.id', 'tutors.first_name')
    ->get();

        // Preparing data for chart
        $labels = $messages->pluck('tutor_name');
        $data = $messages->pluck('avg_messages_per_day');

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    public function studentsWithoutTutors()
    {
        $students = User::whereDoesntHave('studentAllocations', function ($query) {
            $query->where('active', 1);
        })->where('role_id', 1)->get();


        return $students;
    }

    public function getStudentListWithAssignedTutor(Request $request)
    {
        $search = $request->input('search');

        $students = DB::table('users AS students')
            ->select(
                'students.id',
                'students.user_code',
                'students.first_name',
                'students.last_name',
                'students.email',
                DB::raw("COALESCE(CONCAT(tutors.first_name, ' ', tutors.last_name), 'Unassigned') AS tutor_name")
            )
            ->leftJoin('allocation', function ($join) {
                $join->on('students.id', '=', 'allocation.student_id')
                    ->where('allocation.active', 1); // Only active allocations
            })
            ->leftJoin('users AS tutors', 'allocation.tutor_id', '=', 'tutors.id')
            ->where('students.role_id', 1); // RoleID 1 for students


        if (!empty($search)) {
            $students->where(function ($query) use ($search) {
                $query->where('students.first_name', 'like', "%{$search}%")
                    ->orWhere('students.last_name', 'like', "%{$search}%")
                    ->orWhere('students.user_code', 'like', "%{$search}%")
                    ->orWhere('students.email', 'like', "%{$search}%")
                    ->orWhereRaw("COALESCE(CONCAT(tutors.first_name, ' ', tutors.last_name), 'Unassigned') LIKE ?", ["%{$search}%"]);
            });
        }
        $students = $students->orderBy('students.user_code')->get();

        return $students;
    }

    public function getTutorListWithAssignedStudentCount(Request $request)
    {
        $search = $request->input('search');
        $tutors = DB::table('users AS tutors')
            ->select(
                'tutors.id AS tutor_id',
                'tutors.user_code',
                'tutors.first_name',
                'tutors.last_name',
                'tutors.email',
                DB::raw('COUNT(students.id) AS assigned_students_count')
            )
            ->leftJoin('allocation AS a', function ($join) {
                $join->on('a.tutor_id', '=', 'tutors.id')
                    ->where('a.active', 1); // Only active allocations
            })
            ->leftJoin('users AS students', function ($join) {
                $join->on('students.id', '=', 'a.student_id')
                    ->where('students.role_id', 1); // Only students
            })
            ->where('tutors.role_id', 2) // Only tutors
            ->groupBy('tutors.id', 'tutors.user_code', 'tutors.first_name', 'tutors.last_name', 'tutors.email');

        if (!empty($search)) {
            $tutors->where(function ($query) use ($search) {
                $query->where('tutors.first_name', 'like', "%{$search}%")
                    ->orWhere('tutors.last_name', 'like', "%{$search}%")
                    ->orWhere('tutors.user_code', 'like', "%{$search}%")
                    ->orWhere('tutors.email', 'like', "%{$search}%");
            });
        }
        $tutors = $tutors->orderBy('tutors.user_code')->get();

        return $tutors;
    }

    public function getMostActiveUsers($periodDays = null)
    {
        $startDate = Carbon::now()->subDays($periodDays);
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $activeUsers = DB::table('users')
            ->select(
                'users.id',
                'users.user_code',
                'users.first_name',
                'users.last_name',
                'users.email',
                'users.last_login_at',
                DB::raw('COUNT(DISTINCT post.id) as post_count'),
                DB::raw('COUNT(DISTINCT comment.id) as comment_count'),
                DB::raw('COUNT(DISTINCT document.id) as document_count'),
                DB::raw('COUNT(DISTINCT meeting_schedule.id) as meeting_count'),
                DB::raw('(COUNT(DISTINCT post.id) +
                 COUNT(DISTINCT comment.id) +
                 COUNT(DISTINCT document.id) +
                 COUNT(DISTINCT meeting_schedule.id)) as total_activity')
            )
            ->leftJoin('post', function ($join) use ($currentMonth) {
                $join->on('post.post_create_by', '=', 'users.id')
                    ->where('post.is_meeting', 0)
                    ->whereMonth('post.updated_at', $currentMonth);
                // ->where('post.updated_at', '>=', $startDate);
            })
            ->leftJoin('comment', function ($join) use ($currentMonth) {
                $join->on('comment.user_id', '=', 'users.id')
                    ->whereMonth('comment.updated_at', $currentMonth);
                //->where('comment.updated_at', '>=', $startDate);
            })
            ->leftJoin('document', function ($join) use ($currentMonth) {
                $join->on('document.post_id', '=', 'post.id')
                    ->whereMonth('document.updated_at', $currentMonth);
                //->where('document.updated_at', '>=', $startDate);
            })
            ->leftJoin('meeting_schedule', function ($join) use ($currentMonth) {
                $join->on('meeting_schedule.student_id', '=', 'users.id')
                    ->where('meeting_schedule.meeting_status', 'completed')
                    ->whereMonth('meeting_schedule.meeting_date', $currentMonth);
                // ->where('meeting_schedule.meeting_date', '>=', $startDate);
            })
            ->groupBy('users.id', 'users.user_code', 'users.first_name', 'users.last_name', 'users.email', 'users.last_login_at')
            ->orderByDesc('total_activity')
            ->get();
        return $activeUsers;
    }

    public function getTutorMessages($msgOrder = 'desc', $nameOrder = 'asc', $month = 'all')
    {
        // // Default start of the current month
        // $startDate = now()->startOfMonth();
        
        // // Adjust start date based on the selected month
        // if ($month !== 'all') {
        //     $months = [
        //         'jan' => 1,
        //         'feb' => 2,
        //         'mar' => 3,
        //         'apr' => 4,
        //         'may' => 5,
        //         'jun' => 6,
        //         'jul' => 7,
        //         'aug' => 8,
        //         'sept' => 9,
        //         'oct' => 10,
        //         'nov' => 11,
        //         'dec' => 12
        //     ];
        //     $monthNumber = $months[$month] ?? null;
        //     if ($monthNumber) {
        //         $startDate = now()->month($monthNumber)->startOfMonth();
        //     }
        // }
        // \Log::info('start date ' . $startDate . $month);
        // // Query to fetch tutor messages
        // $tutorMessages = DB::table('users AS tutors')
        // ->leftJoin('post', function ($join) use ($startDate) {
        //     $join->on('post.post_create_by', '=', 'tutors.id')
        //         ->orOn('post.post_received_by', '=', 'tutors.id');
        // })
        // ->leftJoin('comment', function ($join) use ($startDate) {
        //     $join->on('comment.post_id', '=', 'post.id')
        //         ->whereIn('comment.user_id', function ($query) {
        //             $query->select('id')
        //                 ->from('users')
        //                 ->where('role_id', 1); // Only students
        //         });
        //       //  ->where('comment.updated_at', '>=', $startDate);
        // })
        //     ->select(
        //         'tutors.id',
        //         'tutors.user_code',
        //         'tutors.first_name',
        //         'tutors.last_name',
        //         'tutors.email',
        //         DB::raw('COUNT(CASE WHEN comment.updated_at >= "' . $startDate->toDateString() . '" THEN comment.id ELSE NULL END) as total_messages'),
        //         DB::raw('ROUND(
        //             COUNT(CASE WHEN comment.updated_at >= "' . $startDate->toDateString() . '" THEN comment.id ELSE NULL END) 
        //             / 
        //             DATEDIFF(NOW(), "' . $startDate->toDateString() . '"), 
        //         2) as avg_messages_per_day')
        //         // DB::raw('COUNT(comment.id) as total_messages'),
        //         // DB::raw('ROUND(COUNT(comment.id) / DATEDIFF(NOW(), \'' . $startDate->toDateString() . '\'), 2) as avg_messages_per_day')
        //     )
            
        //     ->where('tutors.role_id', 2) // Only tutors
        //     ->groupBy('tutors.id', 'tutors.user_code', 'tutors.first_name', 'tutors.last_name', 'tutors.email');

        // // Apply sorting by message count
        // if ($msgOrder === 'asc') {
        //     $tutorMessages->orderBy('total_messages', 'asc');
        // } elseif ($msgOrder === 'desc') {
        //     $tutorMessages->orderBy('total_messages', 'desc');
        // }

        // // Apply sorting by name
        // if ($nameOrder === 'az') {
        //     $tutorMessages->orderBy('tutors.first_name', 'asc');
        // } elseif ($nameOrder === 'za') {
        //     $tutorMessages->orderBy('tutors.first_name', 'desc');
        // }
        // //  \Log::info('start date ' . $tutorMessages);
        // // Fetch and return the results
        // return $tutorMessages->get();
        // Default: start of current year
    $startDate = now()->startOfYear();
    $endDate = now()->endOfMonth(); // default: now (for 'all')
    $daysInMonth = $startDate->daysInMonth;

    // Adjust start and end date based on the selected month
    if ($month !== 'all') {
        $months = [
            'jan' => 1,
            'feb' => 2,
            'mar' => 3,
            'apr' => 4,
            'may' => 5,
            'jun' => 6,
            'jul' => 7,
            'aug' => 8,
            'sept' => 9,
            'oct' => 10,
            'nov' => 11,
            'dec' => 12
        ];

        $monthNumber = $months[$month] ?? null;
        if ($monthNumber) {
            $startDate = now()->startOfYear()->month($monthNumber)->startOfMonth();
            $endDate = now()->startOfYear()->month($monthNumber)->endOfMonth();
        }
    }
    $daysCount = $month === 'all'
    ? $startDate->diffInDays($endDate) + 1// inclusive
    : $startDate->daysInMonth;
    \Log::info('Start Date: ' . $startDate . ' | End Date: ' . $endDate);

    // Query to fetch tutor messages
    $tutorMessages = DB::table('users AS tutors')
        ->leftJoin('post', function ($join) {
            $join->on('post.post_create_by', '=', 'tutors.id')
                ->orOn('post.post_received_by', '=', 'tutors.id');
        })
        ->leftJoin('comment', function ($join) use ($startDate, $endDate){
            $join->on('comment.post_id', '=', 'post.id')
                ->whereIn('comment.user_id', function ($query) {
                    $query->select('id')
                        ->from('users')
                        ->where('role_id', 1); // Only students
                });
                //->whereBetween('comment.updated_at', [$startDate->toDateString(), $endDate->toDateString()]);
        })
        ->select(
            'tutors.id',
            'tutors.user_code',
            'tutors.first_name',
            'tutors.last_name',
            'tutors.email',
            DB::raw('COUNT(CASE WHEN comment.updated_at BETWEEN "' . $startDate->toDateString() . '" AND "' . $endDate->toDateString() . '" THEN comment.id ELSE NULL END) as total_messages'),
            DB::raw('ROUND(
                COUNT(CASE WHEN comment.updated_at BETWEEN "' . $startDate->toDateString() . '" AND "' . $endDate->toDateString() . '" THEN comment.id ELSE NULL END)
                /
                ' . $daysCount . ',
            2) as avg_messages_per_day')
            // DB::raw('ROUND(
            //     COUNT(CASE WHEN comment.updated_at BETWEEN "' . $startDate->toDateString() . '" AND "' . $endDate->toDateString() . '" THEN comment.id ELSE NULL END)
            //     /
            //     ' . $startDate->daysInMonth . ',
            // 2) as avg_messages_per_day')
        )
        ->where('tutors.role_id', 2) // Only tutors
        ->groupBy('tutors.id', 'tutors.user_code', 'tutors.first_name', 'tutors.last_name', 'tutors.email');

    // Apply sorting by message count
    if ($msgOrder === 'asc') {
        $tutorMessages->orderBy('total_messages', 'asc');
    } elseif ($msgOrder === 'desc') {
        $tutorMessages->orderBy('total_messages', 'desc');
    }

    // Apply sorting by name
    if ($nameOrder === 'az') {
        $tutorMessages->orderBy('tutors.first_name', 'asc');
    } elseif ($nameOrder === 'za') {
        $tutorMessages->orderBy('tutors.first_name', 'desc');
    }

    return $tutorMessages->get();
    }

    public function getStudentsWithNoInteraction($noInteractionPeriod = 'all', $selectedDate = null)
    {
        //saving comment to use later (might delete later if not necessary)
        
    //      // Initialize date ranges
    // $cutoffStart = now();
    // $cutoffEnd = null;
    // $olderThan = now()->subDays(7);   // >7 days inactive
    // $newerThan = now()->subDays(30); 
    // // // Set date ranges based on selected period
    // // if ($noInteractionPeriod === '7days') {
    // //     $cutoffStart = now()->subDays(30);  // More than 7 days ago (upper bound: 30)
    // //     $cutoffEnd = now()->subDays(7);     // More recent than 7 days ago (lower bound)
    // // } elseif ($noInteractionPeriod === '30days') {
    // //     $cutoffStart = now()->subDays(60);  // More than 30 days ago (upper bound: 60)
    // //     $cutoffEnd = now()->subDays(30);    // More recent than 30 days ago (lower bound)
    // // } elseif ($noInteractionPeriod === '60days') {
    // //     $cutoffStart = now()->subDays(365); // A year ago (just as a safe upper bound)
    // //     $cutoffEnd = now()->subDays(60);    // More recent than 60 days ago (lower bound)
    // // }
    // if ($noInteractionPeriod === '7days') {
    //     $olderThan = now()->subDays(7);   // >7 days inactive
    //     $newerThan = now()->subDays(30);  // ≤30 days inactive
    // } elseif ($noInteractionPeriod === '30days') {
    //     $olderThan = now()->subDays(30);  // >30 days inactive
    //     $newerThan = now()->subDays(60);  // ≤60 days inactive
    // } elseif ($noInteractionPeriod === '60days') {
    //     $olderThan = now()->subDays(60);  // >60 days inactive
    //     $newerThan = null;                // No upper limit
    // }

    // $query = User::where('role_id', 1)
    //             ->leftJoin('post', 'post.post_create_by', '=', 'users.id')
    //             ->leftJoin('comment', 'comment.user_id', '=', 'users.id')
    //             ->select([
    //                 'users.id',
    //                 'users.user_code',
    //                 'users.first_name',
    //                 'users.last_name',
    //                 'users.email',
    //                 DB::raw('
    //                     GREATEST(
    //                         IFNULL(MAX(post.updated_at), "1970-01-01"),
    //                         IFNULL(MAX(comment.updated_at), "1970-01-01"),
    //                         IFNULL(users.updated_at, "1970-01-01")
    //                     ) as last_active_date'),
    //                 DB::raw('
    //                     CASE 
    //                         WHEN GREATEST(
    //                             IFNULL(MAX(post.updated_at), "1970-01-01"),
    //                             IFNULL(MAX(comment.updated_at), "1970-01-01"),
    //                             IFNULL(users.updated_at, "1970-01-01")
    //                         ) = "1970-01-01"
    //                         THEN DATEDIFF(NOW(), "1970-01-01")
    //                         ELSE DATEDIFF(NOW(), GREATEST(
    //                             IFNULL(MAX(post.updated_at), "1970-01-01"),
    //                             IFNULL(MAX(comment.updated_at), "1970-01-01"),
    //                             IFNULL(users.updated_at, "1970-01-01")
    //                         ))
    //                     END as no_interaction_days'),
    //                     DB::raw('
    //                     CASE 
    //                         WHEN GREATEST(
    //                             IFNULL(MAX(post.updated_at), "1970-01-01"),
    //                             IFNULL(MAX(comment.updated_at), "1970-01-01"),
    //                             IFNULL(users.updated_at, "1970-01-01")
    //                         ) = "1970-01-01"
    //                         THEN "Not active yet"
    //                         ELSE CONCAT(DATEDIFF(NOW(), GREATEST(
    //                             IFNULL(MAX(post.updated_at), "1970-01-01"),
    //                             IFNULL(MAX(comment.updated_at), "1970-01-01"),
    //                             IFNULL(users.updated_at, "1970-01-01")
    //                         )), " days ago")
    //                     END as interaction_label')
    //             ])
               
    //             ->groupBy('users.id', 'user_code', 'first_name', 'last_name', 'email', 'users.updated_at');
              
    // if ($noInteractionPeriod === '60days') {
    //     $query->havingRaw('no_interaction_days > 60');
    // } 
    
    // else {
    //     $query->havingRaw('no_interaction_days > ?', [$olderThan->diffInDays(now())])
    //           ->havingRaw('no_interaction_days <= ?', [$newerThan->diffInDays(now())]);
    // }
    //  $query->where(function($q) use ($olderThan) {
    //     $q->whereDoesntHave('posts', function($q2) use ($olderThan) {
    //           $q2->where('updated_at', '>', $olderThan);
    //        })
    //        ->orWhereDoesntHave('comments', function($q2) use ($olderThan) {
    //           $q2->where('updated_at', '>', $olderThan);
    //        });
    // });
    $baseDate = $selectedDate ?Carbon::parse($selectedDate) : now();

    // Define thresholds relative to base date
    $olderThan = $baseDate->copy()->subDays(7);
   // $newerThan = null;
    $newerThan = $baseDate->copy()->subDays(30);

    if ($noInteractionPeriod === '7days') {
        $olderThan = $baseDate->copy()->subDays(7);   // >7 days inactive
        $newerThan = $baseDate->copy()->subDays(30);  // ≤30 days inactive
    } elseif ($noInteractionPeriod === '30days') {
        $olderThan = $baseDate->copy()->subDays(30);  // >30 days inactive
        $newerThan = $baseDate->copy()->subDays(60);  // ≤60 days inactive
    } elseif ($noInteractionPeriod === '60days') {
        $olderThan = $baseDate->copy()->subDays(60);  // >60 days inactive
        $newerThan = null;                            // No upper limit
    } else if ($noInteractionPeriod == 'all'){
        $olderThan = $baseDate->copy()->subDays(7);  // >60 days inactive
        $newerThan = null; 
    }

    $query = User::where('role_id', 1)
        ->leftJoin('post', 'post.post_create_by', '=', 'users.id')
        ->leftJoin('comment', 'comment.user_id', '=', 'users.id')
        ->select([
            'users.id',
            'users.user_code',
            'users.first_name',
            'users.last_name',
            'users.email',
            DB::raw('
                GREATEST(
                    IFNULL(MAX(post.updated_at), "1970-01-01"),
                    IFNULL(MAX(comment.updated_at), "1970-01-01"),
                    IFNULL(users.updated_at, "1970-01-01")
                ) as last_active_date'),
            DB::raw("
                CASE 
                    WHEN GREATEST(
                        IFNULL(MAX(post.updated_at), '1970-01-01'),
                        IFNULL(MAX(comment.updated_at), '1970-01-01'),
                        IFNULL(users.updated_at, '1970-01-01')
                    ) = '1970-01-01'
                    THEN DATEDIFF('$baseDate', '1970-01-01')
                    ELSE DATEDIFF('$baseDate', GREATEST(
                        IFNULL(MAX(post.updated_at), '1970-01-01'),
                        IFNULL(MAX(comment.updated_at), '1970-01-01'),
                        IFNULL(users.updated_at, '1970-01-01')
                    ))
                END as no_interaction_days"),
            DB::raw("
                CASE 
                    WHEN GREATEST(
                        IFNULL(MAX(post.updated_at), '1970-01-01'),
                        IFNULL(MAX(comment.updated_at), '1970-01-01'),
                        IFNULL(users.updated_at, '1970-01-01')
                    ) = '1970-01-01'
                    THEN 'Not active yet'
                    ELSE CONCAT(DATEDIFF('$baseDate', GREATEST(
                        IFNULL(MAX(post.updated_at), '1970-01-01'),
                        IFNULL(MAX(comment.updated_at), '1970-01-01'),
                        IFNULL(users.updated_at, '1970-01-01')
                    )), ' days ago')
                END as interaction_label")
        ])
        ->groupBy('users.id', 'user_code', 'first_name', 'last_name', 'email', 'users.updated_at');

    // Filter by number of inactive days
    if ($noInteractionPeriod === '60days') {
        $query->havingRaw('no_interaction_days > 60');
    }else if ($noInteractionPeriod === 'all') {
    
        $query->havingRaw('no_interaction_days > 7');
    } 
     else {
        $query->havingRaw('no_interaction_days > ?', [$olderThan->diffInDays($baseDate)])
              ->havingRaw('no_interaction_days <= ?', [$newerThan->diffInDays($baseDate)]);
    }
    

    // Exclude users who had recent posts/comments
    $query->where(function($q) use ($olderThan) {
        $q->whereDoesntHave('posts', function($q2) use ($olderThan) {
            $q2->where('updated_at', '>', $olderThan);
        })->orWhereDoesntHave('comments', function($q2) use ($olderThan) {
            $q2->where('updated_at', '>', $olderThan);
        });
    });


    $students = $query->get();
          

    return $students;


//         // Calculate the cutoff date based on the selected period
//         $cutoffDate = now();
//         if ($noInteractionPeriod === '7days') {
//             $cutoffDate = now()->subDays(7);
//         } elseif ($noInteractionPeriod === '30days') {
//             $cutoffDate = now()->subDays(30);
//         } elseif ($noInteractionPeriod === '60days') {
//             $cutoffDate = now()->subDays(60);
//         }
//         // Query to fetch students with no interaction after the cutoff date
//         $students = DB::table('users AS students')
//             ->select(
//                 'students.id',
//                 'students.user_code',
//                 'students.first_name',
//                 'students.last_name',
//                 'students.email',
//                 DB::raw('COALESCE(GREATEST(MAX(comment.updated_at), MAX(post.updated_at)), students.updated_at) as last_active_date'),
//                 DB::raw('CASE
//                         WHEN COALESCE(GREATEST(MAX(comment.updated_at), MAX(post.updated_at)), students.updated_at) IS NOT NULL
//                         THEN DATEDIFF(NOW(), COALESCE(GREATEST(MAX(comment.updated_at), MAX(post.updated_at)), students.updated_at))
//                         ELSE NULL
//                      END as no_interaction_days')
//             )
//             ->leftJoin('comment', function ($join) use ($cutoffDate, $selectedDate) {
//                 $join->on('comment.user_id', '=', 'students.id')
//                     ->where('comment.updated_at', '<=', $cutoffDate);

//                 if ($selectedDate) {
//                     $join->whereDate('comment.updated_at', '=', $selectedDate);
//                 }
//             })
//             ->leftJoin('post', function ($join) use ($cutoffDate, $selectedDate) {
//                 $join->on('post.post_create_by', '=', 'students.id')
//                     ->where('post.updated_at', '<=', $cutoffDate);

//                 if ($selectedDate) {
//                     $join->whereDate('post.updated_at', '=', $selectedDate);
//                 }
//             })
//             ->where('students.role_id', 1) // Only students
//             ->groupBy('students.id', 'students.user_code', 'students.first_name', 'students.last_name', 'students.email', 'students.updated_at')
//             ->havingRaw('COALESCE(GREATEST(MAX(comment.updated_at), MAX(post.updated_at)), students.updated_at) <= ?', [$cutoffDate])
//             ->orderByDesc('no_interaction_days')
//             ->get();
// // dd($students->count());
//         return $students;
    }
}
