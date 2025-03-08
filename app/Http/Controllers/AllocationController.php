<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPSTORM_META\type;

class AllocationController extends Controller
{
    public function index(){
        $pageTitle = 'Allocation';
        $tutors = User::where('role_id',2)->latest()->get();
        $students = User::whereDoesntHave('studentAllocations', function ($query) {
            $query->where('active', 1);
        })->where('role_id', 1)->get();

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
        $studentCount = Allocation::where('tutor_id',$tutor->id)->where('active',1)->count();
        if($studentCount + $selectedStudentsCount > 15){
            $notify[] = ['Tutor has student limit.'];
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
            if ($studentCount > 15) {
                break;
            }
        }
        return redirect()->route('admin.allocation')->with('success', 'Students have been assigned.');
    }

    public function assignedLists(Request $request){

        $pageTitle = 'Assigned List';
        $allocations = Allocation::with(['student','tutor'])->where('active',1)->get();
        $allocations = json_decode($allocations);
        return view('admin.assignedlists',compact('pageTitle','allocations'));
    }

    public function reallocation(Request $request){
        $pageTitle = "Reallocation";
        $request->validate([
            'selected_allocations' => 'required|array|min:1', // Ensure at least one order is selected
            'selected_allocations.*' => 'exists:allocation,id',   // Validate each selected order ID
        ], [
            'selected_allocations.required' => 'Please select at least one student.',
            'selected_allocations.min' => 'You must select at least one student.',
            'selected_allocations.*.exists' => 'One or more selected students are invalid.',
        ]);
        $selectedAllocationIds = $request->input('selected_allocations');
        // dd(gettype($selectedAllocationIds));
        $allocations = Allocation::whereIn('id',$selectedAllocationIds)->with(['student','tutor'])->where('active',1)->latest()->get();
        $tutors = User::where('role_id',2)->latest()->get();

        return view('admin.reallocation', compact(['pageTitle','selectedAllocationIds','allocations','tutors']));
    }

    public function reallocate(Request $request){
        $request->validate([
            'tutor_id' => 'required',
            'selected_allocations' => 'required|array|min:1', // Ensure at least one order is selected
            'selected_allocations.*' => 'exists:allocation,id',   // Validate each selected order ID
        ], [
            'selected_allocations.required' => 'Please select at least one student.',
            'selected_allocations.min' => 'You must select at least one student.',
            'selected_allocations.*.exists' => 'One or more selected students are invalid.',
        ]);
        $tutor = User::findOrFail($request->tutor_id);

        $selectedAllocationIds = $request->input('selected_allocations');
        $selectedAllocations = Allocation::whereIn('id', $selectedAllocationIds)->get();
        $selectedAllocations = json_decode($selectedAllocations);

        $selectedAllocationCount = count($selectedAllocations);
        $studentCount = Allocation::where('tutor_id',$tutor->id)->where('active',1)->count();
        if($studentCount + $selectedAllocationCount > 15){
            $notify[] = ['Tutor has student limit.'];
            return back()->withErrors($notify);
        }

        foreach($selectedAllocations as $selectedAllocation){
            $id = $selectedAllocation->id;
            $allocation = Allocation::findOrFail($id);
            $allocation->tutor_id = $request->tutor_id;
            $allocation->allocation_date_time = now();
            $allocation->active = 1;
            $allocation->save();


            $studentCount++;
            if ($studentCount > 15) {
                break;
            }
        }

        return redirect()->route('admin.assignedlists')->with('success', 'Reallocation is successful');
    }

    public function deleteAllocation(Request $request){
       $allocation = Allocation::where('active',1)->findOrFail($request->id);
       $allocation->active = 0;
       $allocation->save();

       return back()->with('success', 'Reallocation is deleted');
    }
}
