<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AdminService;
use App\Model\User;
use App\Models\BrowserUsage;
use App\Models\PageView;
use Illuminate\Support\Facades\DB;



class AdminController extends Controller
{

    public function index()
    {
        $pageMap = [

            'admin/dashboard' => 'Admin Dashboard',
            'admin/allocation' => 'Allocation',
            'admin/assignedlists' => 'Assigned list',
            'admin/tutorlists' => 'Tutor List',
            'admin/studentlists' => 'Student List',
            'admin/report' => 'Admin Reports',
            'admin/reallocation' => 'Reallocation',

            'tutor/dashboard' => 'Tutor Dashboard',
            'tutor/meetinglists' => 'Tutor Meetings',
            'tutor/blogging' => 'Tutor Blogging',
            'tutor/report' => 'Tutor Reports',

            'student/dashboard' => 'Student Dashboard',
            'student/meetinglists' => 'Student Meetings',
            'student/blogging' => 'Student Blogging',
            'student/report' => 'Student Reports',
        ];
        $students = $this->adminDashboardService->studentsWithoutTutors();

        $mostViewed = PageView::orderByDesc('view_count')->first();
        $friendlyName = $pageMap[$mostViewed->page_name] ?? $mostViewed->page_name;

        $activeUsers = $this->adminDashboardService->getMostActiveUsers(30);
        $mostActiveUser = $activeUsers->first();

        return view('admin.dashboard', compact('students', 'friendlyName', 'mostActiveUser'));
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

    public function tutorlists(Request $request)
    {
        \Log::info('Page viewed:', ['route' => $request->route()?->getName()]);
        $tutors = $this->adminDashboardService->getTutorListWithAssignedStudentCount($request);
        return view('admin.tutorlists', compact('tutors'));
    }



    public function studentlists(Request $request)
    {
        $students = $this->adminDashboardService->getStudentListWithAssignedTutor($request);

        return view('admin.studentlists', compact('students'));
    }

    public function report(Request $request)
    {
        //     $pagesMap = [
        //         'tutor.meetings' => 'Tutor Meetings',
        //         'student.meetings' => 'Student Meetings',
        //         'tutor.blogs' => 'Tutor Blogging',
        //         'student.blogs' => 'Student Blogging',
        //         'admin.reports' => 'Admin Reports',
        //         'admin.dashboard' => 'Admin Dashboard',
        //         'tutor.dashboard' => 'Tutor Dashboard',
        //         'student.dashboard' => 'Student Dashboard',
        //         'allocation' => 'Allocation',
        //         'reschedule' => 'Reschedule',
        //         'meeting.detail' => 'Meeting Detail',
        //         'assigned.list' => 'Assigned list',
        //         'tutor.list' => 'Tutor List',
        //         'student.list' => 'Student List',
        //         'tutor.reports' => 'Tutor Reports',
        //         'student.reports' => 'Student Reports',
        //         'reallocation' => 'Reallocation'
        //     ];
        //     $pageViews = PageView::whereIn('page_name', array_keys($pagesMap))
        //     ->orderByDesc('view_count')
        //     ->get()
        //     ->map(function ($pageView) use ($pagesMap) {
        //     return [
        //         'name' => $pagesMap[$pageView->page_name] ?? $pageView->page_name,
        //         'count' => $pageView->view_count
        //     ];
        // })
        // ->values(); // reset keys
        //most viewed pages
        $pageMap = [
            'admin/dashboard' => 'Admin Dashboard',
            'admin/allocation' => 'Allocation',
            'admin/assignedlists' => 'Assigned list',
            'admin/tutorlists' => 'Tutor List',
            'admin/studentlists' => 'Student List',
            'admin/report' => 'Admin Reports',
            'admin/reallocation' => 'Reallocation',

            'tutor/dashboard' => 'Tutor Dashboard',
            'tutor/meetinglists' => 'Tutor Meetings',
            'tutor/blogging' => 'Tutor Blogging',
            'tutor/report' => 'Tutor Reports',

            'student/dashboard' => 'Student Dashboard',
            'student/meetinglists' => 'Student Meetings',
            'student/blogging' => 'Student Blogging',
            'student/report' => 'Student Reports',

            // ... add all you want to include
        ];
        $rawViews = DB::table('page_views')->get();

        $mappedViews = [];

        foreach ($rawViews as $view) {
            if (isset($pageMap[$view->page_name])) {
                $friendlyName = $pageMap[$view->page_name];

                if (isset($mappedViews[$friendlyName])) {
                    $mappedViews[$friendlyName] += $view->view_count;
                } else {
                    $mappedViews[$friendlyName] = $view->view_count;
                }
            }
        }

        // Convert to array of objects and sort descending
        $pageViews = collect($mappedViews)
            ->map(function ($count, $name) {
                return ['page_name' => $name, 'view_count' => $count];
            })
            ->sortByDesc('count')
            ->values();

        // Get the time period filter (default: last 30 days)
        $period = $request->input('period', 30);
        $startDate = now()->subDays($period);

        $activeUsers = $this->adminDashboardService->getMostActiveUsers($period);
        // Retrieve query parameters
        $msgOrder = $request->input('msgOrder', 'desc'); // Default to descending order
        $nameOrder = $request->input('nameOrder', 'asc'); // Default to A-Z
        $month = $request->input('month', 'all'); // Default to all months

        // Fetch tutor messages using the service
        $tutorMessages = $this->adminDashboardService->getTutorMessages($msgOrder, $nameOrder, $month);

        // Retrieve query parameters
        $noInteractionPeriod = $request->input('no_interaction', 'all'); // Default to all
        $selectedDate = $request->input('meeting_date'); // Optional date filter

        // Fetch students with no interaction using the service
        $students = $this->adminDashboardService->getStudentsWithNoInteraction($noInteractionPeriod, $selectedDate);

        //$pageViews = PageView::orderBy('view_count', 'desc')->get();
        return view('admin.report', compact('pageViews', 'activeUsers','tutorMessages','students'));
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

    public function getBrowserPieData()
    {
        $data = $this->adminDashboardService->getBrowserPieData();
        return response()->json($data);
    }
    public function getMostViewPageData()
    {
        $data = $this->adminDashboardService->getMostViewPageData();
        return response()->json($data);
    }

    // public function getStudentListWithAssignedTutors(){
    //     $data = $this->adminDashboardService->getStudentListWithAssignedTutor();
    //     return response()->json($data);
    // }

}
