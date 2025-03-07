<?php
namespace App\Services;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Allocation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class AdminService
{
    /**
     * Get the number of students inactive for the given time period.
     *
     * @param int $days
     * @return int
     */
    public function getInactiveStudentsCount(int $days)
    {
        $dateLimit = Carbon::now()->subDays($days);

        // Get the students who have not created or updated posts in the last $days
        $inactiveStudents = User::where('role_id', 1)
            ->whereDoesntHave('posts', function ($query) use ($dateLimit) {
                $query->where('updated_at', '>=', $dateLimit); // Checking post update time
            })
            ->whereDoesntHave('comments', function ($query) use ($dateLimit) {
                $query->where('updated_at', '>=', $dateLimit);
            })
            // ->whereDoesntHave('posts.documents', function ($query) use ($dateLimit) {
            //     $query->where('updated_at', '>=', $dateLimit);
            // })
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
            'inactive_7_days' => $this->getInactiveStudentsCount(7),
            'inactive_30_days' => $this->getInactiveStudentsCount(30),
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
        $students = User::where('role_id', 1)
            ->whereNotIn('id', function ($query) {
                $query->select('student_id')
                      ->from('allocation');
            })
            ->get();


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
            ->leftJoin('allocation', 'students.id', '=', 'allocation.student_id')
            ->leftJoin('users AS tutors', 'allocation.tutor_id', '=', 'tutors.id')
            ->where('students.role_id', 1); // RoleID 1 for students
            
        
            if ($search) {
                $students->where(function ($query) use ($search) {
                    $query->where('students.first_name', 'like', "%{$search}%")
                        ->orWhere('students.last_name', 'like', "%{$search}%")
                        ->orWhere('students.user_code', 'like', "%{$search}%")
                        ->orWhere('students.email', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT(tutors.first_name, ' ', tutors.last_name) LIKE ?", ["%{$search}%"]);
                });
            }
            $students = $students->orderBy('students.user_code')->get();

        return $students;
        
    }

    public function getTutorListWithAssignedStudentCount()
    {
        $results = DB::table('users AS tutors')
        ->select(
            'tutors.id AS tutor_id',
            'tutors.user_code',
            'tutors.first_name',
            'tutors.last_name',
            'tutors.email',
            DB::raw('COUNT(students.id) AS assigned_students_count')
        )
        ->leftJoin('allocation AS a', 'a.tutor_id', '=', 'tutors.id')
        ->leftJoin('users AS students', function ($join) {
            $join->on('students.id', '=', 'a.student_id')
                ->where('students.role_id', 1); // Only students
        })
        ->where('tutors.role_id', 2) // Only tutors
        ->groupBy('tutors.id', 'tutors.user_code','tutors.first_name','tutors.last_name','tutors.email')
        ->orderBy('tutors.user_code')
        ->get();

    return $results;
        
    }
    

}
