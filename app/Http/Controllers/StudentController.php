<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeetingSchedule;
use App\Models\Allocation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class StudentController extends Controller
{
    public function index()
    {
        return view('student.dashboard');
    }

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
        ->orderBy('meeting_schedules.meeting_date')
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
        return view('student.meetinglists', compact('meeting_schedules','assignedTutor'));
        
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
        $currentStudent = $id? User::find($meeting_schedules->tutor_id):null;
       

       // $readOnly = request()->routeIs('tutor.meetingdetail.update') ? false : true;

        if($id) {
            // $resource = Resource::findOrFail($id);
            $readOnly = false;
            $isTutorAllocated = Allocation::where('student_id', $meeting_schedules->student_id)
            ->where('tutor_id', $meeting_schedules->tutor_id)
            ->where('active', 1)
            ->exists();
            return view('student.meetingdetail', compact('id','assignedTutor','meeting_schedules','readOnly','isTutorAllocated'));
        }
        // For create (no ID), just pass null or empty data
        return view('student.meetingdetail', ['id' => null,'assignedTutor' => $assignedTutor,'meeting_schedules'=>$meeting_schedules,'readOnly'=>false,'currentStudent'=>null,'isTutorAllocated'=>false]);

    }

    public function meetingview($id=null) {
        
        $studentId = Auth::id(); // Get logged-in tutor’s ID
        $assignedTutor = Allocation::where('student_id', $studentId)
                ->where('active', 1)
                ->with('tutor') 
                ->get();

        if( $id) {
            // $resource = Resource::findOrFail($id);
            $meeting_schedules = MeetingSchedule::findOrFail($id);
           // $currentStudent = User::find($meeting_schedules->student_id);
            
            $isTutorAllocated = Allocation::where('student_id', $meeting_schedules->student_id)
            ->where('tutor_id', $meeting_schedules->tutor_id)
            ->where('active', 1)
            ->exists();
            
            $readOnly = true;
            return view('student.meetingdetail', compact('id','meeting_schedules','readOnly','assignedTutor','isTutorAllocated'));
        }
        $meeting_schedules=null;
        
        // For create (no ID), just pass null or empty data
        return view('student.meetingdetail', ['id' => null,'meeting_schedules' =>$meeting_schedules,'assignedTutor'=>$assignedTutor,'isTutorAllocated'=>false]);
    }

    public function save(Request $request,$id=null)
    {
        $studentId = Auth::id();
        $assignedTutor = Allocation::where('student_id', $studentId)->first();

            
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
                'meeting_status' =>"New",
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
    
            
            return redirect()->route('student.meetinglists')->with('success', 'Meeting created!');
        }

        
    }
    public function toggleStatus($id){
        $meeting = MeetingSchedule::findOrFail($id);

        // Toggle status between "completed" and "new"
        $meeting->meeting_status = $meeting->meeting_status === 'completed' ? 'new' : 'completed';
        $meeting->save();
        return redirect()->route('student.meetinglists')->with('success', 'Meeting status updated successfully!')->header('Cache-Control', 'no-store');
      //  return redirect()->back()->with('success', 'Meeting status updated successfully!');
    }
    public function cancelMeeting(Request $request){
        $meeting = MeetingSchedule::findOrFail($request->id);
        $meeting->meeting_status = 'cancelled';
        $meeting->save();
       // $meeting->delete();

        return redirect()->route('student.meetinglists')->with('success', 'Meeting is cancelled!');
    }
    
}
