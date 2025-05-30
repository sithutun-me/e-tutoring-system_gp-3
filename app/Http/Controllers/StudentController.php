<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeetingSchedule;
use App\Models\Allocation;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

class StudentController extends Controller
{
    protected $studentId;
    public function __construct()
    {
        $this->resolveStudentId();
    }
    private function resolveStudentId()
    {
        //$isStudent = Route::currentRouteName() !== 'admin.student.dashboard';

        $this->studentId = request()->route('id') ?? Auth::id();

        // // Authorization check
        // if (request()->route('id') && !Auth::user()->isAdmin()) {
        //     abort(403, 'Unauthorized');
        // }
    }
    public function index($id = null)
    {
        // $studentId = $id;
        // if($studentId) {

        // }else{
        //     $studentId = auth()->id();
        // }
        $studentId = $this->studentId;
        $routeName = Route::currentRouteName();
        $isStudent = true;

        if ($routeName === 'admin.student.dashboard') {
            $isStudent = false;
        }

        $tutorId = DB::table('allocation')
            ->where('student_id', $studentId)
            ->where('active', 1)
            ->value('tutor_id');

        $tutorName = DB::table('users')
            ->where('id', $tutorId)
            ->selectRaw('CONCAT(first_name, " ", last_name) as full_name')
            ->value('full_name');


        $postCount = DB::table('post')
            ->where('post_create_by', $tutorId)
            ->where('post_received_by', $studentId)
            ->where('is_meeting', 0)
            ->where('post_status', 'new') //case sensitive
            ->count();

        //getting upcoming meeting list within one week.

        $meetings = DB::table('meeting_schedule')
            ->leftjoin('users as students', 'meeting_schedule.student_id', '=', 'students.id')
            ->where('meeting_schedule.tutor_id', $tutorId)
            ->where('meeting_schedule.meeting_status', 'new')
            ->where('meeting_schedule.meeting_date', '<=', Carbon::now()->addDays(7))
            ->orderBy('meeting_schedule.meeting_date', 'asc')
            ->select(
                'meeting_schedule.id',
                'meeting_schedule.meeting_title',
                'meeting_schedule.meeting_type',
                'meeting_schedule.meeting_date',
                'meeting_schedule.meeting_start_time',
                'meeting_schedule.meeting_end_time',
                'meeting_schedule.updated_at',
                'students.first_name',
                'students.last_name'
            )
            ->get();
        return view('student.dashboard', compact('postCount', 'meetings', 'tutorName', 'isStudent'));
    }
    //for dashboard
    public function getMeetingPieData($id = null)
    {

        $studentId =  $id ?? auth()->id();

        $currentMonth = Carbon::now()->month;;
        $today = Carbon::now();
        $meetingCounts = DB::table('meeting_schedule')
            ->where('student_id', $studentId)
            ->whereMonth('meeting_date', $currentMonth)
            ->select(
                DB::raw("COUNT(CASE WHEN meeting_status = 'completed' THEN 1 END) as completed"),
                DB::raw("COUNT(CASE WHEN meeting_status = 'new' THEN 1 END) as new"),
                DB::raw("COUNT(CASE WHEN meeting_status = 'cancelled' THEN 1 END) as cancelled"),
                DB::raw("COUNT(CASE WHEN meeting_status = 'overdue' THEN 1 END) as overdue")
            )
            ->first();

        // Format data for Pie Chart
        $chartData = [
            'labels' => ['Completed', 'New', 'Cancelled', 'Overdue'],
            'data' => [$meetingCounts->completed, $meetingCounts->new, $meetingCounts->cancelled, $meetingCounts->overdue]
        ];

        return response()->json($chartData);
    }

    public function myActivities($id = null)
    {

        $studentId =  $id ?? auth()->id();
        $currentMonth = Carbon::now()->month;

        $tutorId = DB::table('allocation')
            ->where('student_id', $studentId)
            ->where('active', 1)
            ->value('tutor_id');

        $postCount = DB::table('post')
            ->where('post_create_by', $studentId)
            ->where('post_received_by', $tutorId)
            ->where('post_status', '!=', 'deleted')
            ->whereMonth('updated_at', $currentMonth)
            ->count();

        $commentCount = DB::table('comment')
            ->join('post', 'comment.post_id', '=', 'post.id')
            ->where('comment.user_id', $studentId) // Comment is made by the student
            ->whereMonth('comment.updated_at', $currentMonth)
            ->where(function ($query) use ($studentId, $tutorId) {
                $query->where(function ($q) use ($studentId, $tutorId) {
                    // Case 1: Post created by tutor and received by student
                    $q->where('post.post_create_by', $tutorId) // Tutor is creator
                        ->where('post.post_received_by', $studentId);  // Student is receiver
                })->orWhere(function ($q) use ($studentId, $tutorId) {
                    // Case 2: Post created by student and received by tutor
                    $q->where('post.post_create_by', $studentId)     // Student is creator
                        ->where('post.post_received_by', $tutorId); // Tutor is receiver
                });
            })
            ->count();

        // table join
        // $commentCount = DB::table('comment')
        //     ->where('user_id', $studentId)
        //     ->whereMonth('updated_at',$currentMonth)
        //     ->count();

        // $commentCount = DB::table('comment')
        //     ->join('post', 'comment.post_id', '=', 'post.id')
        //     ->where('comment.user_id', $studentId)
        //     ->whereMonth('comment.updated_at',$currentMonth)
        //     ->count();

        $documentCount = DB::table('document')
            ->join('post', 'document.post_id', '=', 'post.id')
            ->where('post.post_create_by', $studentId)
            ->whereMonth('document.updated_at', $currentMonth)
            ->count();
        $interactionCounts = [
            'labels' => ['Posts', 'Comments', 'Documents'],
            'data' => [$postCount, $commentCount, $documentCount]
        ];
        return response()->json($interactionCounts);
    }
    public function tutorActivities($id = null)
    {
        $studentId =  $id ?? auth()->id();
        $currentMonth = Carbon::now()->month;


        $tutorId = DB::table('allocation')
            ->where('student_id', $studentId)
            ->where('active', 1)
            ->value('tutor_id');

        $postCount = DB::table('post')
            ->where('post_create_by', $tutorId)
            ->where('post_received_by', $studentId)
            ->where('post_status', '!=', 'deleted')
            ->whereMonth('updated_at', $currentMonth)
            ->count();

        //create both
        $commentCount = DB::table('comment')
            ->join('post', 'comment.post_id', '=', 'post.id')
            ->where('comment.user_id', $tutorId) // Comment is made by the student
            ->whereMonth('comment.updated_at', $currentMonth)
            ->where(function ($query) use ($studentId, $tutorId) {
                $query->where(function ($q) use ($studentId, $tutorId) {
                    // Case 1: Post created by tutor and received by student
                    $q->where('post.post_create_by', $tutorId) // Tutor is creator
                        ->where('post.post_received_by', $studentId);  // Student is receiver
                })->orWhere(function ($q) use ($studentId, $tutorId) {
                    // Case 2: Post created by student and received by tutor
                    $q->where('post.post_create_by', $studentId)     // Student is creator
                        ->where('post.post_received_by', $tutorId); // Tutor is receiver
                });
            })
            ->count();
        // $commentCount = DB::table('comment')
        //     ->join('post', 'comment.post_id', '=', 'post.id')
        //     ->where('comment.user_id', $tutorId)
        //     ->where('post.post_create_by',$tutorId)
        //     ->where('post.post_received_by',$studentId)
        //     ->whereMonth('comment.updated_at',$currentMonth)
        //     ->count();

        $documentCount = DB::table('document')
            ->join('post', 'document.post_id', '=', 'post.id')
            ->where('post.post_create_by', $tutorId)
            ->where('post.post_received_by', $studentId)
            ->whereMonth('document.updated_at', $currentMonth)
            ->count();
        $interactionCounts = [
            'labels' => ['Posts', 'Comments', 'Documents'],
            'data' => [$postCount, $commentCount, $documentCount]
        ];
        return response()->json($interactionCounts);
    }



    //for meeting schedule
    public function meetinglists(Request $request)
    {
        $studentId = Auth::id();

        $assignedTutor = Allocation::where('student_id', $studentId)
            ->where('active', 1)
            ->with('tutor')
            ->get();
        $assignedTutorIds = $assignedTutor->pluck('tutor.id')->toArray();


        $this->overdueStatus();

        $query = DB::table('meeting_schedule as meeting_schedules')
            ->join('users as tutor', 'meeting_schedules.tutor_id', '=', 'tutor.id')
            ->select(
                'meeting_schedules.id',
                'meeting_schedules.meeting_title',
                'meeting_schedules.meeting_date',
                'meeting_schedules.meeting_start_time',
                'meeting_schedules.meeting_end_time',
                'meeting_schedules.meeting_type',
                'meeting_schedules.meeting_platform',
                'meeting_schedules.meeting_link',
                'meeting_schedules.meeting_location',
                'meeting_schedules.meeting_status',
                'tutor.id as tutorId',
                'tutor.user_code as tutor_id',
                'tutor.first_name',
                'tutor.last_name'
            )
            ->where('meeting_schedules.student_id', $studentId);



        // Filter by meeting type if selected
        if ($request->filled('meeting_type') && $request->meeting_type !== 'All') {
            $query->where('meeting_schedules.meeting_type', $request->meeting_type);
        }

        // Filter by date if selected
        if ($request->filled('meeting_date')) {
            $query->where('meeting_schedules.meeting_date', $request->meeting_date);
        }

        // Filter by student if selected
        // if ($request->filled('student_id')) {
        //     $query->where('meeting_schedules.student_id', $request->student_id);
        // }

        // Get results and group by date
        $meeting_schedules = $query
            ->orderBy('meeting_schedules.meeting_date', 'desc')
            ->orderBy('meeting_schedules.meeting_start_time')
            ->get()
            ->groupBy('meeting_date');
        // if(is_null($meeting_schedules)){
        //     return view('tutor.meetinglists',compact('meeting_schedules'));
        // }
        foreach ($meeting_schedules as $date => $meetings) {
            foreach ($meetings as $meeting) {
                $meeting->isTutorAssigned = in_array($meeting->tutorId, $assignedTutorIds);
            }
        }
        return view('student.meetinglists', compact('meeting_schedules', 'assignedTutor'));
    }
    public function overdueStatus()
    {
        $now = Carbon::now();

        // Fetch meetings
        $meetings = DB::table('meeting_schedule')
            ->orderBy('meeting_date')
            ->orderBy('meeting_start_time')
            ->whereNotIn('meeting_status', ['completed', 'cancelled'])
            ->get();

        foreach ($meetings as $meeting) {
            $meetingEndDateTime = Carbon::parse($meeting->meeting_date . ' ' . $meeting->meeting_end_time);

            if ($meetingEndDateTime->isPast()) {
                DB::table('meeting_schedule')
                    ->where('id', $meeting->id)
                    ->update(['meeting_status' => 'overdue']);
            }
        }

        //  return view('tutor.meetinglists', compact('meeting_schedules', 'students'));
    }
    //create or update view
    public function meetingdetail($id = null)
    {
        \Log::info('Meetingdetail called with ID: ' . $id);
        $studentId = Auth::id(); // Get logged-in tutor’s ID
        // $userRole = DB::table('users') // Assuming your users table is named "users"
        //             ->where('id', $tutorId)
        //             ->value('role_id'); // Retrieve the 'role' column value
        // if ($userRole === 2) {// User is a tutor
        //     //$tutorId = $userId; // Assign the user ID to $tutorId
        //     // ... your tutor-specific logic ...
        // } elseif ($userRole === 1) {
        //     $students = Allocation::where('tutor_id', $tutorId)
        //         ->where('active', 1)
        //         ->with('student') // Assuming you have a relationship
        //         ->get();
        // }
        $assignedTutor = Allocation::where('student_id', $studentId)
            ->where('active', 1)
            ->with('tutor') // Assuming you have a relationship
            ->get();


        $meeting_schedules = $id ? MeetingSchedule::find($id) : null;

        $currentTutor = $id ? User::find($meeting_schedules->tutor_id) : null;
        // $readOnly = request()->routeIs('tutor.meetingdetail.update') ? false : true;

        if ($id) {
            // $resource = Resource::findOrFail($id);
            $readOnly = false;
            $isTutorAllocated = Allocation::where('student_id', $meeting_schedules->student_id)
                ->where('tutor_id', $meeting_schedules->tutor_id)
                ->where('active', 1)
                ->exists();
            return view('student.meetingdetail', compact('id', 'assignedTutor', 'meeting_schedules', 'readOnly', 'currentTutor', 'isTutorAllocated'));
        }
        // For create (no ID), just pass null or empty data
        return view('student.meetingdetail', ['id' => null, 'assignedTutor' => $assignedTutor, 'meeting_schedules' => $meeting_schedules, 'readOnly' => false, 'currentTutor' => null, 'isTutorAllocated' => false]);
    }

    public function meetingview($id = null)
    {

        $studentId = Auth::id(); // Get logged-in tutor’s ID
        $assignedTutor = Allocation::where('student_id', $studentId)
            ->where('active', 1)
            ->with('tutor')
            ->get();

        if ($id) {
            // $resource = Resource::findOrFail($id);
            $meeting_schedules = MeetingSchedule::findOrFail($id);
            // $currentStudent = User::find($meeting_schedules->student_id);
            $currentTutor = $id ? User::find($meeting_schedules->tutor_id) : null;
            $isTutorAllocated = Allocation::where('student_id', $meeting_schedules->student_id)
                ->where('tutor_id', $meeting_schedules->tutor_id)
                ->where('active', 1)
                ->exists();

            $readOnly = true;
            return view('student.meetingdetail', compact('id', 'meeting_schedules', 'readOnly', 'assignedTutor', 'currentTutor', 'isTutorAllocated'));
        }
        $meeting_schedules = null;

        // For create (no ID), just pass null or empty data
        return view('student.meetingdetail', ['id' => null, 'meeting_schedules' => $meeting_schedules, 'assignedTutor' => $assignedTutor, 'currentTutor' => null, 'isTutorAllocated' => false]);
    }

    public function save(Request $request, $id = null)
    {
        $studentId = Auth::id();
        $assignedTutor = Allocation::where('student_id', $studentId)
            ->where('active', 1)->first();


        $request->validate([
            'meeting_title' => 'required|string|max:50',
            'meeting_description' => 'nullable|string|max:255',
            'meeting_type' => 'required|in:real,virtual',
            'meeting_platform' => 'nullable|string|required_if:meeting_type,virtual|max:255',
            'meeting_link' => 'nullable|url|required_if:meeting_type,virtual',
            'meeting_location' => 'nullable|string|required_if:meeting_type,real|max:255',
            // 'meeting_date' =>
            // $id
            // ? 'required|date' // For updates
            // : 'required|date|after_or_equal:today', // For creates

            'meeting_date' => 'required|date|after_or_equal:today',
            'meeting_start_time' => [
                'required',
                'date_format:h:i A', // Validate the correct time format
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->meeting_date) {
                        // Combine date and time and set the correct timezone
                        $meetingDateTime = Carbon::createFromFormat('Y-m-d h:i A', $request->meeting_date . ' ' . $value, 'Asia/Yangon');

                        // Get current date and time in the same timezone
                        $currentDateTime = Carbon::now('Asia/Yangon');

                        if ($meetingDateTime->isBefore($currentDateTime)) {
                            $fail('The meeting start time must be a time in the future.');
                        }
                    }
                }
            ],
            'meeting_end_time' => 'required|date_format:h:i A|after:meeting_start_time',


        ]);


        $start_time = date("H:i", strtotime($request->meeting_start_time));
        $end_time = date("H:i", strtotime($request->meeting_end_time));


        if ($id) {

            $meeting = MeetingSchedule::findOrFail($id);
            $meeting->update([
                'meeting_title' => $request->meeting_title,
                'meeting_type' => $request->meeting_type,
                'meeting_date' => $request->meeting_date,
                'meeting_start_time' => $start_time,
                'meeting_end_time' => $end_time,
                'meeting_description' => $request->meeting_description,
                'meeting_status' => "New",
                'tutor_id' => $assignedTutor->tutor_id,
                'student_id' => Auth::id(),
                'meeting_location' => $request->meeting_type === 'real' ? $request->meeting_location : null,
                'meeting_platform' => $request->meeting_type === 'virtual' ? $request->meeting_platform : null,
                'meeting_link' => $request->meeting_type === 'virtual' ? $request->meeting_link : null,
            ]);


            return redirect()->route('student.meetinglists')->with('success', 'Meeting updated!');
        } else {


            $meeting = MeetingSchedule::create([
                'meeting_title' => $request->meeting_title,
                'meeting_type' => $request->meeting_type,
                'meeting_date' => $request->meeting_date,
                'meeting_start_time' => $start_time,
                'meeting_end_time' => $end_time,
                'meeting_description' => $request->meeting_description,
                'meeting_status' => "New",
                'tutor_id' => $assignedTutor->tutor_id,
                'student_id' => Auth::id(),
                'meeting_location' => $request->meeting_type === 'real' ? $request->meeting_location : null,
                'meeting_platform' => $request->meeting_type === 'virtual' ? $request->meeting_platform : null,
                'meeting_link' => $request->meeting_type === 'virtual' ? $request->meeting_link : null,
            ]);

            $post = Post::create([
                'post_create_by' => Auth::id(),
                'post_received_by' => $assignedTutor->tutor_id,
                'post_title' => $request->meeting_title,
                'post_status' => "new",
                'post_description' => $request->meeting_description,
                'is_meeting' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('student.meetinglists')->with('success', 'Meeting created!');
        }
    }
    public function toggleStatus($id)
    {
        $meeting = MeetingSchedule::findOrFail($id);

        // Toggle status between "completed" and "new"
        $meeting->meeting_status = $meeting->meeting_status === 'completed' ? 'new' : 'completed';
        $meeting->save();
        return redirect()->route('student.meetinglists')->with('success', 'Meeting status updated successfully!')->header('Cache-Control', 'no-store');
        //  return redirect()->back()->with('success', 'Meeting status updated successfully!');
    }
    public function cancelMeeting(Request $request)
    {
        $meeting = MeetingSchedule::findOrFail($request->id);
        $meeting->meeting_status = 'cancelled';
        $meeting->save();
        // $meeting->delete();

        return redirect()->route('student.meetinglists')->with('success', 'Meeting is cancelled!');
    }

    public function report(Request $request)
    {
        $studentId = auth()->id();
        $tutorId = DB::table('allocation')
            ->where('student_id', $studentId)
            ->where('active', 1)
            ->value('tutor_id');

        $tutorName = DB::table('users')
            ->where('id', $tutorId)
            ->selectRaw('CONCAT(first_name, " ", last_name) as full_name')
            ->value('full_name');

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month; // Get the current month (1 = Jan, 2 = Feb, etc.)

        // Fetch activities grouped by month
        $studentActivities = DB::table('post')
            ->selectRaw("MONTH(updated_at) as month, COUNT(*) as posts")
            ->where('post_create_by', $studentId)
            ->where('post_status', '!=', 'deleted')
            ->whereYear('updated_at', $currentYear)
            ->groupBy('month')
            ->pluck('posts', 'month');

        $commentCounts = DB::table('comment')
            ->selectRaw("MONTH(comment.updated_at) as month, COUNT(*) as comments")
            ->join('post', 'comment.post_id', '=', 'post.id')
            ->where('comment.user_id', $studentId) // Comment is made by the student
            ->whereYear('comment.updated_at', $currentYear)
            ->where(function ($query) use ($studentId, $tutorId) {
                $query->where(function ($q) use ($studentId, $tutorId) {
                    // Case 1: Post created by tutor and received by student
                    $q->where('post.post_create_by', $tutorId) // Tutor is creator
                        ->where('post.post_received_by', $studentId);  // Student is receiver
                })->orWhere(function ($q) use ($studentId, $tutorId) {
                    // Case 2: Post created by student and received by tutor
                    $q->where('post.post_create_by', $studentId)     // Student is creator
                        ->where('post.post_received_by', $tutorId); // Tutor is receiver
                });
            })
            ->groupBy('month')
            ->pluck('comments', 'month');
        // $commentCounts = DB::table('comment')
        //     ->selectRaw("MONTH(updated_at) as month, COUNT(*) as comments")
        //     ->where('user_id', $studentId)
        //     ->whereYear('updated_at', $currentYear)
        //     ->groupBy('month')
        //     ->pluck('comments', 'month');

        $documentCounts = DB::table('document')
            ->join('post', 'document.post_id', '=', 'post.id')
            ->selectRaw("MONTH(document.updated_at) as month, COUNT(*) as documents")
            ->where('post.post_create_by', $studentId)
            ->where('post.post_received_by', $tutorId)
            ->whereYear('document.updated_at', $currentYear)
            ->groupBy('month')
            ->pluck('documents', 'month');

        $meetingCounts = DB::table('meeting_schedule')
            ->selectRaw("MONTH(meeting_date) as month, COUNT(*) as meetings")
            ->where('tutor_id', $tutorId)
            ->where('student_id', $studentId)
            ->where('meeting_status', 'completed')
            ->whereYear('updated_at', $currentYear)
            ->groupBy('month')
            ->pluck('meetings', 'month');

        // Fetch tutor data
        $tutorActivities = DB::table('post')
            ->selectRaw("MONTH(updated_at) as month, COUNT(*) as posts")
            ->where('post_create_by', $tutorId)
            ->where('post_received_by', $studentId)
            ->where('post_status', '!=', 'deleted')
            ->whereYear('updated_at', $currentYear)
            ->groupBy('month')
            ->pluck('posts', 'month');

        $tutorCommentCounts = DB::table('comment')
            ->selectRaw("MONTH(comment.updated_at) as month, COUNT(*) as comments")
            ->join('post', 'comment.post_id', '=', 'post.id')
            ->where('comment.user_id', $tutorId) // Comment is made by the student
            ->whereYear('comment.updated_at', $currentYear)
            ->where(function ($query) use ($studentId, $tutorId) {
                $query->where(function ($q) use ($studentId, $tutorId) {
                    // Case 1: Post created by tutor and received by student
                    $q->where('post.post_create_by', $tutorId) // Tutor is creator
                        ->where('post.post_received_by', $studentId);  // Student is receiver
                })->orWhere(function ($q) use ($studentId, $tutorId) {
                    // Case 2: Post created by student and received by tutor
                    $q->where('post.post_create_by', $studentId)     // Student is creator
                        ->where('post.post_received_by', $tutorId); // Tutor is receiver
                });
            })
            ->groupBy('month')
            ->pluck('comments', 'month');
        // $tutorCommentCounts = DB::table('comment')
        //     ->join('post', 'comment.post_id', '=', 'post.id')
        //     ->selectRaw("MONTH(comment.updated_at) as month, COUNT(*) as comments")
        //     ->where('comment.user_id', $tutorId)
        //     ->where('post.post_create_by', $tutorId)
        //     ->where('post.post_received_by', $studentId)
        //     ->whereYear('comment.updated_at', $currentYear)
        //     ->groupBy('month')
        //     ->pluck('comments', 'month');

        $tutorDocumentCounts = DB::table('document')
            ->join('post', 'document.post_id', '=', 'post.id')
            ->selectRaw("MONTH(document.updated_at) as month, COUNT(*) as documents")
            ->where('post.post_create_by', $tutorId)
            ->where('post.post_received_by', $studentId)
            ->whereYear('document.updated_at', $currentYear)
            ->groupBy('month')
            ->pluck('documents', 'month');

        $tutorMeetingCounts = DB::table('meeting_schedule')
            ->selectRaw("MONTH(meeting_date) as month, COUNT(*) as meetings")
            ->where('student_id', $studentId)
            ->where('meeting_status', 'completed')
            ->whereYear('updated_at', $currentYear)
            ->groupBy('month')
            ->pluck('meetings', 'month');

        // Prepare months and monthly data
        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];

        $studentMonthlyData = [];
        $tutorMonthlyData = [];

        foreach ($months as $monthNum => $monthName) {
            if ($monthNum > $currentMonth) {
                break; // Stop adding months if they exceed the current month
            }

            $studentMonthlyData[] = [
                'month' => $monthName,
                'posts' => $studentActivities[$monthNum] ?? 0,
                'comments' => $commentCounts[$monthNum] ?? 0,
                'documents' => $documentCounts[$monthNum] ?? 0,
                'meetings' => $meetingCounts[$monthNum] ?? 0
            ];

            $tutorMonthlyData[] = [
                'month' => $monthName,
                'posts' => $tutorActivities[$monthNum] ?? 0,
                'comments' => $tutorCommentCounts[$monthNum] ?? 0,
                'documents' => $tutorDocumentCounts[$monthNum] ?? 0,
                'meetings' => $tutorMeetingCounts[$monthNum] ?? 0
            ];
        }

        return view('student.report', compact('studentMonthlyData', 'tutorMonthlyData', 'tutorName'));
    }

    public function blogging()
    {
        return view('student.blogging');
    }


    public function createpost()
    {
        return view('student.createpost');
    }

    public function updatepost()
    {
        return view('student.updatepost');
    }
}
