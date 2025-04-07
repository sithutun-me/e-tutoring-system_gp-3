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
        $startDate = Carbon::now()->subDays($minDays);
        $endDate = $maxDays ? Carbon::now()->subDays($maxDays) : null;


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

            ->count();

        return $inactiveStudents;
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
        $messages = DB::table('users AS tutors')
            ->select(
                'tutors.first_name AS tutor_name',
                DB::raw('COUNT(comment.id) AS message_count')
            )
            ->leftJoin('post', function ($join) {
                $join->on('post.post_create_by', '=', 'tutors.id')
                    ->orOn('post.post_received_by', '=', 'tutors.id');
            })
            ->leftJoin('comment', function ($join) use ($timeFrame) {
                $join->on('comment.post_id', '=', 'post.id')
                    ->whereIn('comment.user_id', function ($query) {
                        $query->select('id')
                            ->from('users')
                            ->where('role_id', 1); // Only students
                    })
                    ->where('comment.created_at', '>=', now()->subMonths($timeFrame));
            })
            ->where('tutors.role_id', 2) // Only tutors
            ->groupBy('tutors.id', 'tutors.first_name')
            ->get();

        // Preparing data for chart
        $labels = $messages->pluck('tutor_name');
        $data = $messages->pluck('message_count');

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

    public function getMostActiveUsers($periodDays = 30)
    {
        $startDate = Carbon::now()->subDays($periodDays);
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
            ->leftJoin('post', function ($join) use ($startDate) {
                $join->on('post.post_create_by', '=', 'users.id')
                    ->where('post.is_meeting', 0)
                    ->where('post.updated_at', '>=', $startDate);
            })
            ->leftJoin('comment', function ($join) use ($startDate) {
                $join->on('comment.user_id', '=', 'users.id')
                    ->where('comment.updated_at', '>=', $startDate);
            })
            ->leftJoin('document', function ($join) use ($startDate) {
                $join->on('document.post_id', '=', 'post.id')
                    ->where('document.updated_at', '>=', $startDate);
            })
            ->leftJoin('meeting_schedule', function ($join) use ($startDate) {
                $join->on('meeting_schedule.student_id', '=', 'users.id')
                    ->where('meeting_schedule.meeting_status', 'completed')
                    ->where('meeting_schedule.meeting_date', '>=', $startDate);
            })
            ->groupBy('users.id', 'users.user_code', 'users.first_name', 'users.last_name', 'users.email', 'users.last_login_at')
            ->orderByDesc('total_activity')
            ->get();
        return $activeUsers;
    }
    public function getTutorMessages($msgOrder = 'desc', $nameOrder = 'asc', $month = 'all')
    {
        // Default start of the current month
        $startDate = now()->startOfMonth();

        // Adjust start date based on the selected month
        if ($month !== 'all') {
            $months = [
                'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4, 'may' => 5, 'jun' => 6,
                'jul' => 7, 'aug' => 8, 'sept' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12
            ];
            $monthNumber = $months[$month] ?? null;
            if ($monthNumber) {
                $startDate = now()->month($monthNumber)->startOfMonth();
            }
        }

        // Query to fetch tutor messages
        $tutorMessages = DB::table('users AS tutors')
            ->select(
                'tutors.id',
                'tutors.user_code',
                'tutors.first_name',
                'tutors.last_name',
                'tutors.email',
                DB::raw('COUNT(comment.id) as total_messages'),
                DB::raw('ROUND(COUNT(comment.id) / DATEDIFF(NOW(), \'' . $startDate->toDateString() . '\'), 2) as avg_messages_per_day')
            )
            ->leftJoin('post', function ($join) use ($startDate) {
                $join->on('post.post_create_by', '=', 'tutors.id')
                    ->orOn('post.post_received_by', '=', 'tutors.id');
            })
            ->leftJoin('comment', function ($join) use ($startDate) {
                $join->on('comment.post_id', '=', 'post.id')
                    ->whereIn('comment.user_id', function ($query) {
                        $query->select('id')
                            ->from('users')
                            ->where('role_id', 1); // Only students
                    })
                    ->where('comment.created_at', '>=', $startDate);
            })
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

        // Fetch and return the results
        return $tutorMessages->get();
    }
}
