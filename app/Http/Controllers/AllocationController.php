<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\User;
use Illuminate\Http\Request;

class AllocationController extends Controller
{
    public function index(){
        $pageTitle = 'Allocation';
        $tutors = User::where('role_id',2)->latest()->get();
        $students = User::where('role_id',1)->latest()->paginate(5);

        return view('admin.allocation',compact('pageTitle','tutors','students'));
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
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
        dd($selectedStudentIds);
        if( $selectedStudentIds == null){
            return back()->withErrors('Student is not choosen');
        }
        $selectedStudents = User::whereIn('id',$selectedStudentIds)->get();
        // dd($selectedStudentIds);

        if($validated){
            return back()->withErrors($validated);
        }

        // $allocation = new Allocation;
        // $allocation->student_id = request()->student_id;
        // $allocation->tutor_id = request()->tutor_id;
        // $allocation->allocation_date_time = request()->student_id;
        // $allocation->student_id = request()->student_id;
        // $allocation->student_id = request()->student_id;
        return redirect('/admin/assignedlists');
    }
}
