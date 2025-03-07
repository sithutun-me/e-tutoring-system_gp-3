<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllocationController extends Controller
{
    public function index(){
        $pageTitle = 'Allocation';
        $tutors = User::where('role_id',2)->latest()->get();
        $students = User::doesntHave('studentAllocations')->where('role_id',1)->get();

        return view('admin.allocation',compact('pageTitle','tutors','students'));
    }

    public function allocate(Request $request)
    {
        $request->validate([
            'tutor_id' => 'required',
            'selected_students' => 'required|array|min:1', // Ensure at least one order is selected
            'selected_students.*' => 'exists:users,id',   // Validate each selected order ID
        ], [
            'tutor_id.required' => 'Please select tutor.',
            'selected_students.required' => 'Please select at least one student.',
            'selected_students.min' => 'You must select at least one student.',
            'selected_students.*.exists' => 'One or more selected students are invalid.',
        ]);
        $selectedStudentIds = $request->input('selected_students');
        $tutor = User::findOrFail($request->tutor_id);

        $selectedStudents = User::whereIn('id',$selectedStudentIds)->get();
        $selectedStudents = json_decode($selectedStudents);

        // dd(count($selectedStudents),$tutor,Auth::user()->id);
        $selectedStudentsCount = count($selectedStudents);
        $studentCount = Allocation::where('tutor_id',$tutor->id)->count();
        if($studentCount + $selectedStudentsCount > 15){
            $notify[] = ['warning', 'Tutor has student limit.'];
            return back()->withErrors($notify);
        }

        foreach($selectedStudents as $selectedStudent){
            $existingStudent = Allocation::where('student_id',$selectedStudent->id)->exists();
            if(!$existingStudent){

                $allocation = new Allocation();
                $allocation->student_id = $selectedStudent->id;
                $allocation->tutor_id = $tutor->id;
                $allocation->allocation_date_time = now();
                $allocation->staff_id = Auth::user()->id;
                $allocation->active = 1;
                $allocation->save();
            }
            $studentCount++;
            if ($studentCount >= 15) {
                break;
            }
        }
        return redirect()->route('admin.allocation')->with('success', 'Students have been assigned.');
    }

    public function assignedLists(Request $request){

        $pageTitle = 'Assigned List';
        $allocations = Allocation::with(['staff','tutor','student'])->get();
        $allocations = json_decode($allocations);
        return view('admin.assignedlists',compact('pageTitle','allocations'));
    }

    public function filter(Request $request){
        $pageTitle = 'Search students';
        $tutors = User::where('role_id',2)->latest()->get();
        $query = User::doesntHave('studentAllocations');

        if($request->filled('search')) {
            $query->where('user_code', 'LIKE', '%' . $request->input('search') . '%' )
            ->orWhere('first_name', 'LIKE', '%' . $request->input('search') . '%')
            ->orWhere('last_name', 'LIKE', '%' . $request->input('search') . '%')
            ->orWhere('email', 'LIKE', '%' . $request->input('search') . '%');
        }
        $students = $query->where('role_id',1)->get();

        return view('admin.allocation', compact('pageTitle','tutors','students'));
    }
}
