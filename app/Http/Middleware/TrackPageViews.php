<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PageView;



class TrackPageViews
{
    
    public function handle(Request $request, Closure $next): Response
    {
        // $pageName = $request->route()->getName() ?? $request->path();

        // // Check if the page already exists in the database
        // $pageView = PageView::where('page_name', $pageName)->first();

        // if ($pageView) {
        //     // Increment view count
        //     $pageView->increment('view_count');
        // } else {
        //     // Create a new record for the page
        //     PageView::create([
        //         'page_name' => $pageName,
        //         'view_count' => 1
        //     ]);
        // }

        // $routeName = $request->route()?->getName(); // get the named route
        // if ($routeName) {
        //     PageView::updateOrCreate(
        //         ['page_name' => $routeName],
        //         ['view_count' => \DB::raw('view_count + 1')]
        //     );
        // }

        try {
            
            $pageName = $request->route()?->getName() ?? $request->path();

            $pageView = PageView::firstOrCreate(
                ['page_name' => $pageName],
                ['view_count' => 0]
            );

            $pageView->increment('view_count');

            \Log::info('Page view recorded for:', ['page' => $pageName]);
        } catch (\Exception $e) {
            \Log::error('Page view tracking failed:', ['error' => $e->getMessage()]);
        }
        // if (auth()->check()) {
        //     $routeName = $request->route()?->getName();
    
        //     $pageMap = [
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
        //         'reallocation' => 'Reallocation',
        //     ];
    
        //     if ($routeName && isset($pageMap[$routeName])) {
        //         $pageName = $pageMap[$routeName];
    
        //         // Save to DB or update count
        //         \App\Models\PageView::updateOrCreate(
        //             ['page_name' => $pageName],
        //             ['view_count' => \DB::raw('view_count + 1')]
        //         );
        //     }
        // }
    
        return $next($request);
    }
}
