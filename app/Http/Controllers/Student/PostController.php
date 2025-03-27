<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    //
    public function index(Request $request)
    {
        $pageTitle = 'Posts';
        $student = Auth::user();
        $studentId = $student->id; // Get the logged-in tutor’s ID
        $query = Post::with(['documents', 'creator', 'receiver', 'comments'])
            ->where('post_status', 'new')
            ->orWhere('post_status', 'updated');
        // dd($tutorId);

        $searchKeyword = $request->input('search_post');
        // Filter by post by if selected to MyPosts
        if ($request->filled('post_by') && $request->post_by == 'MyPosts') {
            $query->where('post_create_by', $studentId);

            // Filter by specific student if 'student_filter' is provided
            if ($request->filled('student_filter')) {
                $studentId = $request->student_filter;

                $query->where(function ($q) use ($studentId) {
                    $q->where('post_create_by', $studentId)
                        ->orWhere('post_received_by', $studentId);
                });
            }

            // Filter by post by if Post Title
            if ($searchKeyword) {
                $query->where(function ($q) use ($searchKeyword) {
                    $q->where('post_title', 'LIKE', '%' . $searchKeyword . '%');
                });
            }
        }
        // Filter by post_by if selected as 'StudentPosts'
        if ($request->filled('post_by') && $request->post_by == 'StudentPosts') {
            $query->where(function ($q) {
                $q->whereHas('creator', function ($subQuery) {
                    $subQuery->where('role_id', 1);
                })
                    ->orWhereHas('receiver', function ($subQuery) {
                        $subQuery->where('role_id', 1);
                    });
            });

            // Filter by specific student if 'student_filter' is provided
            if ($request->filled('student_filter')) {
                $studentId = $request->student_filter;

                $query->where(function ($q) use ($studentId) {
                    $q->where('post_create_by', $studentId);
                });
            }

            // Filter by post by if Post Title
            if ($searchKeyword) {
                $query->where(function ($q) use ($searchKeyword) {
                    $q->where('post_title', 'LIKE', '%' . $searchKeyword . '%');
                });
            }
        }

        // Filter by post by if selected to MyPosts
        if ($request->filled('post_by') && $request->post_by == 'All') {
            // Filter by specific student if 'student_filter' is provided
            if ($request->filled('student_filter')) {
                $studentId = $request->student_filter;
                dd($request->input('student_filter'));
                $query->where(function ($q) use ($studentId) {
                    $q->where('post_create_by', $studentId)
                        ->orWhere('post_received_by', $studentId);
                });
            }

            // Filter by post by if Post Title
            if ($searchKeyword) {
                $query->where(function ($q) use ($searchKeyword) {
                    $q->where('post_title', 'LIKE', '%' . $searchKeyword . '%');
                });
            }
        }

        $posts = $query->orderBy('updated_at', 'desc')->get();
        $students = $query = User::whereHas('studentAllocations', function ($query) use ($studentId) {
            $query->where('tutor_id', $studentId)->where('active', 1);
        })->where('role_id', 1)->get();

        \Log::info('back to blogging: '); // Log success
        return view('student.blogging', compact(['pageTitle', 'posts', 'students', 'student']));
    }
}
