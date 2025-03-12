<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeetingSchedule;
use App\Models\Allocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class TutorController extends Controller
{
    public function index()
    {
        return view('tutor.dashboard');
    }
    public function meetinglists(Request $request)
    {   
        $tutorId = Auth::id(); // Get the logged-in tutor’s ID
        
        $students = Allocation::where('tutor_id', $tutorId)
                ->where('active', 1)
                ->with('student') // Assuming you have a relationship
                ->get();
        $meeting_schedules = DB::table('meeting_schedules')
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
            ->where('meeting_schedules.tutor_id', $tutorId)
        //  ->where('meeting_schedules.active', 1)
            ->orderBy('meeting_schedules.meeting_date')
            ->orderBy('meeting_schedules.meeting_start_time')
            ->get()
            ->groupBy('meeting_date'); // Group by date
            // if(is_null($meeting_schedules)){
            //     return view('tutor.meetinglists',compact('meeting_schedules'));
            // }   
        return view('tutor.meetinglists', compact('meeting_schedules','students'));
        
    }
    //create or update view
    public function meetingdetail($id = null) {
        \Log::info('Meetingdetail called with ID: ' . $id);
        $tutorId = Auth::id(); // Get logged-in tutor’s ID
        $students = Allocation::where('tutor_id', $tutorId)
                ->where('active', 1)
                ->with('student') // Assuming you have a relationship
                ->get();
        $meeting_schedules = $id ? MeetingSchedule::find($id) : null;
       // $readOnly = request()->routeIs('tutor.meetingdetail.update') ? false : true;

        if($id) {
            // $resource = Resource::findOrFail($id);
            $readOnly = false;
            return view('tutor.meetingdetail', compact('id','students','meeting_schedules','readOnly'));
        }
        // For create (no ID), just pass null or empty data
        return view('tutor.meetingdetail', ['id' => null,'students' => $students,'meeting_schedules'=>$meeting_schedules,'readOnly'=>false]);

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
            $readOnly = true;
            return view('tutor.meetingdetail', compact('id','students','meeting_schedules','readOnly'));
        }
        $meeting_schedules=null;
        // For create (no ID), just pass null or empty data
        return view('tutor.meetingdetail', ['id' => null,'students' => $students,'meeting_schedules' =>$meeting_schedules]);
    }

    //update or create function 
    public function save(Request $request,$id=null)
    {
        $request->validate([
            'meeting_title' => 'required|string|max:255',
            'meeting_description' => 'nullable|string',
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
                function ($attribute, $value, $fail) {
                    // Get the current date and time
                    $currentDateTime = \Carbon\Carbon::now();
                    
                    // Combine today's date with the entered start time
                    $startTime = \Carbon\Carbon::createFromFormat('Y-m-d h:i A', $currentDateTime->toDateString() . ' ' . $value);
                    
                    // Check if the start time is in the future
                    if ($startTime->isBefore($currentDateTime)) {
                        $fail('The ' . $attribute . ' must be a time in the future.');
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
        $meeting->delete();

        return redirect()->route('tutor.meetinglists')->with('success', 'Meeting is canceled!');
    }

    
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
