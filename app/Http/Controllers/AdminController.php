<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\DashboardService;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
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


    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Return inactive students data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInactiveStudentsData()
    {
       // return response()->json(['message' => 'API is working']);

        $data = $this->dashboardService->getInactiveStudentsData();

        return response()->json($data);  // Return as JSON for API
    }

    public function getAverageMessage()
    {
       // return response()->json(['message' => 'API is working']);

        $data = $this->dashboardService->getAverageMessagesPerTutor(1); // past 30 days (1 month)

        return response()->json($data);  // Return as JSON for API
    }


}
