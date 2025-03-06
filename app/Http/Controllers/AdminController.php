<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AdminService;
use App\Model\User;

class AdminController extends Controller
{
    public function index()
    {
        $students = $this->adminDashboardService->studentsWithoutTutors();


        return view('admin.dashboard', compact('students'));
    }

    public function allocation()
    {
        return view('admin.allocation');
    }
    public function assignedlists()
    {
        return view('admin.assignedlists');
    }
    public function reallocation()
    {
        return view('admin.reallocation');
    }

    public function tutorlists()
    {
        $tutors = $this->adminDashboardService->getTutorListWithAssignedStudentCount();
        return view('admin.tutorlists',compact('tutors'));
    }


<<<<<<< HEAD
    
    public function studentlists(Request $request)
=======

    public function studentlists()
>>>>>>> 5b5d709 (allocation and assignedlist)
    {
        $students = $this->adminDashboardService->getStudentListWithAssignedTutor($request);
       
        return view('admin.studentlists',compact('students'));
    }


    protected $adminDashboardService;

    public function __construct(AdminService $adminDashboardService)
    {
        $this->adminDashboardService = $adminDashboardService;
    }

    /**
     * Return inactive students data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInactiveStudentsData()
    {
       // return response()->json(['message' => 'API is working']);

        $data = $this->adminDashboardService->getInactiveStudentsData();

        return response()->json($data);  // Return as JSON for API
    }

    public function getAverageMessage()
    {
       // return response()->json(['message' => 'API is working']);

        $data = $this->adminDashboardService->getAverageMessagesPerTutor(1); // past 30 days (1 month)

        return response()->json($data);  // Return as JSON for API
    }

<<<<<<< HEAD
    // public function getStudentListWithAssignedTutors(){
    //     $data = $this->adminDashboardService->getStudentListWithAssignedTutor();
    //     return response()->json($data);
    // }

    
=======

>>>>>>> 5b5d709 (allocation and assignedlist)

}
