<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeetingSchedule;
use App\Models\Allocation;
use App\Models\Post;
use App\Models\User;
use App\Rules\FileTypeValidate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;


class TutorController extends Controller
{
    public function index()
    {
        $tutorId = Auth::id();

        //getting upcoming meeting list within one week.
        $oneWeek = Carbon::now()->subDays(7);
        $meetings = DB::table('meeting_schedule')
                    ->leftjoin('users as students', 'meeting_schedule.student_id', '=', 'students.id')
                    ->where('meeting_schedule.tutor_id', $tutorId)
                    ->where('meeting_schedule.meeting_status', 'new')
                    ->where('meeting_schedule.meeting_date', '<=', Carbon::now()->addDays(7))
                    ->orderBy('meeting_schedule.meeting_date','asc')
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

        return view('tutor.dashboard',compact('meetings'));
    }

    public function interactionCounts(Request $request) {
        $tutorId = Auth::id(); // Get logged-in tutor ID
        $startOfMonth = Carbon::now()->startOfMonth(); // First day of the current month
        $today = Carbon::now(); // Current day
        $filter = $request->query('interaction_type', 'All');
       // $filter = 'Posts';
        // Get students assigned to the tutor (max 15)
        $students = Allocation::where('tutor_id', $tutorId)
            ->where('active', 1)
            ->with('student')
            ->get();

        // Prepare an array to store interaction counts
        $interactionCounts = [];

        foreach ($students as $student) {
            $studentId = $student->student_id;

            // Interaction counts based on filter
            $postCount = $commentCount = $documentCount = $meetingCount = 0;

            if ($filter === 'All' || $filter === 'Posts') {
                $postCount = DB::table('post')
                    ->where('post_create_by', $studentId)
                    ->where('is_meeting',0)
                    ->whereBetween('updated_at', [$startOfMonth, $today])
                    ->count();
            }

            if ($filter === 'All' || $filter === 'Comments') {
                $commentCount = DB::table('comment')
                    ->where('user_id', $studentId)
                    ->whereBetween('updated_at', [$startOfMonth, $today])
                    ->count();
            }

            if ($filter === 'All' || $filter === 'Documents') {
                $documentCount = DB::table('document')
                    ->join('post', 'document.post_id', '=', 'post.id')
                    ->where('post.post_create_by', $studentId)
                    ->whereBetween('document.updated_at', [$startOfMonth, $today])
                    ->count();
            }

            if ($filter === 'All' || $filter === 'Meetings') {
                $meetingCount = DB::table('meeting_schedule')
                    ->where('student_id', $studentId)
                    ->where('tutor_id', $tutorId)
                    ->where('meeting_status', 'completed')
                    ->whereBetween('updated_at', [$startOfMonth, $today])
                    ->count();
            }

            // Total interactions based on filter
            $totalInteractions = $postCount + $commentCount + $documentCount + $meetingCount;

            // Store in array
            $interactionCounts[] = [
                'student' => $student->student,
                'interactions' => $totalInteractions
            ];
        }

        return response()->json($interactionCounts);
        // // Sort by highest interaction count
        // usort($interactionCounts, function ($a, $b) {
        //     return $b['interactions'] - $a['interactions'];
        // });

        // return view('tutor.interaction_list', compact('interactionCounts'));
    }




    //meeting code start
    public function meetinglists(Request $request)
    {
        $tutorId = Auth::id(); // Get the logged-in tutor’s ID

        $students = Allocation::where('tutor_id', $tutorId)
                ->where('active', 1)
                ->with('student') // Assuming you have a relationship
                ->get();
        $this->overdueStatus();

        $query = DB::table('meeting_schedule as meeting_schedules')
            ->join('users as students', 'meeting_schedules.student_id', '=', 'students.id')
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
                'students.user_code as student_id',
                'students.first_name',
                'students.last_name'
            )
            ->where('meeting_schedules.tutor_id', $tutorId);

            // Filter by meeting type if selected
    if ($request->filled('meeting_type') && $request->meeting_type !== 'All') {
        $query->where('meeting_schedules.meeting_type', $request->meeting_type);
    }

    // Filter by date if selected
    if ($request->filled('meeting_date')) {
        $query->where('meeting_schedules.meeting_date', $request->meeting_date);
    }

    // Filter by student if selected
    if ($request->filled('student_id')) {
        $query->where('meeting_schedules.student_id', $request->student_id);
    }

    // Get results and group by date
    $meeting_schedules = $query
        ->orderBy('meeting_schedules.meeting_date')
        ->orderBy('meeting_schedules.meeting_start_time')
        ->get()
        ->groupBy('meeting_date');
            // if(is_null($meeting_schedules)){
            //     return view('tutor.meetinglists',compact('meeting_schedules'));
            // }


        return view('tutor.meetinglists', compact('meeting_schedules','students'));

    }
    public function overdueStatus(){
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
    public function meetingdetail($id = null) {
        \Log::info('Meetingdetail called with ID: ' . $id);
        $tutorId = Auth::id(); // Get logged-in tutor’s ID
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
        $students = Allocation::where('tutor_id', $tutorId)
                ->where('active', 1)
                ->with('student') // Assuming you have a relationship
                ->get();
        $meeting_schedules = $id ? MeetingSchedule::find($id) : null;
        $currentStudent = $id? User::find($meeting_schedules->student_id):null;


       // $readOnly = request()->routeIs('tutor.meetingdetail.update') ? false : true;

        if($id) {
            // $resource = Resource::findOrFail($id);
            $readOnly = false;
            $isStudentAllocated =  Allocation::where('student_id', $meeting_schedules->student_id)
            ->where('active', 1)
            ->exists();
            return view('tutor.meetingdetail', compact('id','students','meeting_schedules','readOnly','currentStudent','isStudentAllocated'));
        }
        // For create (no ID), just pass null or empty data
        return view('tutor.meetingdetail', ['id' => null,'students' => $students,'meeting_schedules'=>$meeting_schedules,'readOnly'=>false,'currentStudent'=>null,'isStudentAllocated'=>false]);

    }
    //showing detail form with data for reschedule
    public function meetingview($id=null) {

        $tutorId = Auth::id(); // Get logged-in tutor’s ID
        $students = Allocation::where('tutor_id', $tutorId)
                ->where('active', 1)
                ->with('student') // Assuming you have a relationship
                ->get();

        if( $id) {
            // $resource = Resource::findOrFail($id);
            $meeting_schedules = MeetingSchedule::findOrFail($id);
            $currentStudent = User::find($meeting_schedules->student_id);

            $isStudentAllocated =  Allocation::where('student_id', $meeting_schedules->student_id)
            ->where('active', 1)
            ->exists();
            $readOnly = true;
            return view('tutor.meetingdetail', compact('id','students','meeting_schedules','readOnly','currentStudent','isStudentAllocated'));
        }
        $meeting_schedules=null;
        $currentStudent=null;
        // For create (no ID), just pass null or empty data
        return view('tutor.meetingdetail', ['id' => null,'students' => $students,'meeting_schedules' =>$meeting_schedules,'currentStudent'=>$currentStudent,'isStudentAllocated'=>false]);
    }


    public function blogging(Request $request)
    {
        $pageTitle = "Posts";
        $posts = Post::with(['documents', 'creator', 'receiver', 'comments'])->orderBy('updated_at', 'desc')->get();
        $tutor = Auth::user();
        $tutorId = $tutor->id;
        // dd($tutor->id);
        $students = $query = User::whereHas('studentAllocations', function ($query) use ($tutorId) {
            $query->where('tutor_id', $tutorId)->where('active', 1);
        })->where('role_id', 1)->get();
        return view('tutor.blogging', compact(['pageTitle', 'posts', 'students', 'tutor']));
    }

    public function createposts(Request $request)
    {
        $pageTitle = "Create Post";
        $tutor = Auth::user();
        $tutorId = $tutor->id;
        $students = $query = User::whereHas('studentAllocations', function ($query) use ($tutorId) {
            $query->where('tutor_id', $tutorId)->where('active', 1);
        })->where('role_id', 1)->get();
        return view('tutor.createposts', compact(['pageTitle', 'tutor', 'students']));
    }

    public function saveposts(Request $request, $id)
    {
        $request->validate([
            'selected_student' => 'required',
            'post_title' => 'required|string|max:50',
            'post_desc' => 'nullable|string|max:255',
            'post_files' => ['nullable','image',new FileTypeValidate(['pdf','word','excel','jpg','jpeg','png'])]
        ]);
        $post = new Post();
        if ($request->hasFile('post_files')) {
            try {

                $post->documents->doc_name = fileUploader($request->logo, getFilePath('category'), getFileSize('category'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        return redirect()->route('tutor.blogging')->with('Success', 'Post upload success!');
    }

    public function updateposts()
    {
        return view('tutor.updateposts');
    }

    public function report()
    {
        return view('tutor.report');
    }

    public function postFilter(Request $request)
    {
        $pageTitle = "Post Search";
        $query = Post::with(['documents', 'creator', 'receiver', 'comments']);
        if ($request->filled('post_by') == 'student' ) {
            $query->where('post_create_by', $request->input('post_by'));
            $query->orWhere('post_received_by', $request->input('post_by'));
        }
        if ($request->filled('student_filter')) {
            $query->where('post_create_by', $request->input('student_filter'));
            $query->orWhere('post_received_by', $request->input('student_filter'));
        }

        $posts = $query->orderBy('created_at', 'desc')->get();
        $tutor = Auth::user();
        $tutorId = $tutor->id;
        $students = $query = User::whereHas('studentAllocations', function ($query) use ($tutorId) {
            $query->where('tutor_id', $tutorId)->where('active', 1);
        })->where('role_id', 1)->get();
        return view('tutor.blogging', compact(['pageTitle', 'posts', 'students', 'tutor']));
    }


    //update or create function
    public function save(Request $request,$id=null)
    {
        $request->validate([
            'meeting_title' => 'required|string|max:255',
            'meeting_description' => 'nullable|string|max:500',
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

            'student_id' => 'required|exists:users,id',
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
                'meeting_status' =>"New",
                'student_id' => $request->student_id,
                'tutor_id' => Auth::id(),
                'meeting_location' => $request->meeting_type === 'real' ? $request->meeting_location : null,
                'meeting_platform' => $request->meeting_type === 'virtual' ? $request->meeting_platform : null,
                'meeting_link' => $request->meeting_type === 'virtual' ? $request->meeting_link : null,
            ]);


            return redirect()->route('tutor.meetinglists')->with('success', 'Meeting updated!');
        } else {


            $meeting = MeetingSchedule::create([
                'meeting_title' => $request->meeting_title,
                'meeting_type' => $request->meeting_type,
                'meeting_date' => $request->meeting_date,
                'meeting_start_time' => $start_time,
                'meeting_end_time' => $end_time,
                'meeting_description' => $request->meeting_description,
                'meeting_status' => "New",
                'student_id' => $request->student_id,
                'tutor_id' => Auth::id(),
                'meeting_location' => $request->meeting_type === 'real' ? $request->meeting_location : null,
                'meeting_platform' => $request->meeting_type === 'virtual' ? $request->meeting_platform : null,
                'meeting_link' => $request->meeting_type === 'virtual' ? $request->meeting_link : null,
            ]);


            return redirect()->route('tutor.meetinglists')->with('success', 'Meeting created!');
        }

    }
    public function toggleStatus($id){
        $meeting = MeetingSchedule::findOrFail($id);

        // Toggle status between "completed" and "new"
        $meeting->meeting_status = $meeting->meeting_status === 'completed' ? 'new' : 'completed';
        $meeting->save();
        return redirect()->route('tutor.meetinglists')->with('success', 'Meeting status updated successfully!')->header('Cache-Control', 'no-store');
      //  return redirect()->back()->with('success', 'Meeting status updated successfully!');
    }
    public function cancelMeeting(Request $request){
        $meeting = MeetingSchedule::findOrFail($request->id);
        $meeting->meeting_status = 'cancelled';
        $meeting->save();
       // $meeting->delete();

        return redirect()->route('tutor.meetinglists')->with('success', 'Meeting is cancelled!');
    }
    //meeting code end

    // public function meetingdetail($id = null)
    // {
    //     $isEdit = $id ? true : false;
    //     return view('tutor.meetingdetail',compact('isEdit','id'));
    // }
    // public function meetingcreate(Request $request)
    // {
    //     return view('tutor.meetingdetail');
    // }
    // public function meetingupdate(Request $request, $id)
    // {
    //     return redirect()->route('tutor.meetingdetail.update', ['id' => $id]);
    // }

}
