<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeetingSchedule;
use App\Models\Allocation;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Models\Document;
use App\Rules\FileTypeValidate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class TutorController extends Controller
{
    public function index()
    {
        $tutorId = Auth::id();

        //getting upcoming meeting list within one week.
        $oneWeek = Carbon::now()->subDays(7);
        $meetings = DB::table('meeting_schedule')
            ->leftjoin('users as students', 'meeting_schedule.student_id', '=', 'students.id')
            ->where('meeting_schedule.tutor_id', $tutorId)
            ->where('meeting_schedule.meeting_status', 'new')
            ->where('meeting_schedule.meeting_date', '<=', Carbon::now()->addDays(7))
            ->orderBy('meeting_schedule.meeting_date', 'asc')
            ->select(
                'meeting_schedule.id',
                'meeting_schedule.meeting_title',
                'meeting_schedule.meeting_type',
                'meeting_schedule.meeting_date',
                'meeting_schedule.meeting_start_time',
                'meeting_schedule.meeting_end_time',
                'meeting_schedule.updated_at',
                'students.first_name',
                'students.last_name'
            )
            ->get();

        return view('tutor.dashboard', compact('meetings'));
        
    }

    public function interactionCounts(Request $request)
    {
        $tutorId = Auth::id(); // Get logged-in tutor ID
        $startOfMonth = Carbon::now()->startOfMonth(); // First day of the current month
        $today = Carbon::now(); // Current day
        $filter = $request->query('interaction_type', 'All');
        // $filter = 'Posts';
        // Get students assigned to the tutor (max 15)
        $students = Allocation::where('tutor_id', $tutorId)
            ->where('active', 1)
            ->with('student')
            ->get();

        // Prepare an array to store interaction counts
        $interactionCounts = [];

        foreach ($students as $student) {
            $studentId = $student->student_id;

            // Interaction counts based on filter
            $postCount = $commentCount = $documentCount = $meetingCount = 0;

            if ($filter === 'All' || $filter === 'Posts') {
                $postCount = DB::table('post')
                    ->where('post_create_by', $studentId)
                    ->where('is_meeting', 0)
                    ->whereBetween('updated_at', [$startOfMonth, $today])
                    ->count();
            }

            if ($filter === 'All' || $filter === 'Comments') {
                $commentCount = DB::table('comment')
                    ->where('user_id', $studentId)
                    ->whereBetween('updated_at', [$startOfMonth, $today])
                    ->count();
            }

            if ($filter === 'All' || $filter === 'Documents') {
                $documentCount = DB::table('document')
                    ->join('post', 'document.post_id', '=', 'post.id')
                    ->where('post.post_create_by', $studentId)
                    ->whereBetween('document.updated_at', [$startOfMonth, $today])
                    ->count();
            }

            if ($filter === 'All' || $filter === 'Meetings') {
                $meetingCount = DB::table('meeting_schedule')
                    ->where('student_id', $studentId)
                    ->where('tutor_id', $tutorId)
                    ->where('meeting_status', 'completed')
                    ->whereBetween('updated_at', [$startOfMonth, $today])
                    ->count();
            }

            // Total interactions based on filter
            $totalInteractions = $postCount + $commentCount + $documentCount + $meetingCount;

            // Store in array
            $interactionCounts[] = [
                'student' => $student->student,
                'interactions' => $totalInteractions
            ];
        }

        return response()->json($interactionCounts);
        // // Sort by highest interaction count
        // usort($interactionCounts, function ($a, $b) {
        //     return $b['interactions'] - $a['interactions'];
        // });

        // return view('tutor.interaction_list', compact('interactionCounts'));
    }




    //meeting code start
    public function meetinglists(Request $request)
    {
        $tutorId = Auth::id(); // Get the logged-in tutor’s ID

        $students = Allocation::where('tutor_id', $tutorId)
            ->where('active', 1)
            ->with('student') // Assuming you have a relationship
            ->get();
        $this->overdueStatus();

        $query = DB::table('meeting_schedule as meeting_schedules')
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
            ->where('meeting_schedules.tutor_id', $tutorId);

        // Filter by meeting type if selected
        if ($request->filled('meeting_type') && $request->meeting_type !== 'All') {
            $query->where('meeting_schedules.meeting_type', $request->meeting_type);
        }

        // Filter by date if selected
        if ($request->filled('meeting_date')) {
            $query->where('meeting_schedules.meeting_date', $request->meeting_date);
        }

        // Filter by student if selected
        if ($request->filled('student_id')) {
            $query->where('meeting_schedules.student_id', $request->student_id);
        }

        // Get results and group by date
        $meeting_schedules = $query
            ->orderBy('meeting_schedules.meeting_date', 'desc')
            ->orderBy('meeting_schedules.meeting_start_time')
            ->get()
            ->groupBy('meeting_date');
        // if(is_null($meeting_schedules)){
        //     return view('tutor.meetinglists',compact('meeting_schedules'));
        // }


        return view('tutor.meetinglists', compact('meeting_schedules', 'students'));
    }
    public function overdueStatus()
    {
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
    public function meetingdetail($id = null)
    {
        \Log::info('Meetingdetail called with ID: ' . $id);
        $tutorId = Auth::id(); // Get logged-in tutor’s ID
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
        $students = Allocation::where('tutor_id', $tutorId)
            ->where('active', 1)
            ->with('student') // Assuming you have a relationship
            ->get();
        $meeting_schedules = $id ? MeetingSchedule::find($id) : null;
        $currentStudent = $id ? User::find($meeting_schedules->student_id) : null;


        // $readOnly = request()->routeIs('tutor.meetingdetail.update') ? false : true;

        if ($id) {
            // $resource = Resource::findOrFail($id);
            $readOnly = false;
            $isStudentAllocated =  Allocation::where('student_id', $meeting_schedules->student_id)
                ->where('active', 1)
                ->exists();
            return view('tutor.meetingdetail', compact('id', 'students', 'meeting_schedules', 'readOnly', 'currentStudent', 'isStudentAllocated'));
        }
        // For create (no ID), just pass null or empty data
        return view('tutor.meetingdetail', ['id' => null, 'students' => $students, 'meeting_schedules' => $meeting_schedules, 'readOnly' => false, 'currentStudent' => null, 'isStudentAllocated' => false]);
    }
    //showing detail form with data for reschedule
    public function meetingview($id = null)
    {

        $tutorId = Auth::id(); // Get logged-in tutor’s ID
        $students = Allocation::where('tutor_id', $tutorId)
            ->where('active', 1)
            ->with('student') // Assuming you have a relationship
            ->get();

        if ($id) {
            // $resource = Resource::findOrFail($id);
            $meeting_schedules = MeetingSchedule::findOrFail($id);
            $currentStudent = User::find($meeting_schedules->student_id);

            $isStudentAllocated =  Allocation::where('student_id', $meeting_schedules->student_id)
                ->where('active', 1)
                ->exists();
            $readOnly = true;
            return view('tutor.meetingdetail', compact('id', 'students', 'meeting_schedules', 'readOnly', 'currentStudent', 'isStudentAllocated'));
        }
        $meeting_schedules = null;
        $currentStudent = null;
        // For create (no ID), just pass null or empty data
        return view('tutor.meetingdetail', ['id' => null, 'students' => $students, 'meeting_schedules' => $meeting_schedules, 'currentStudent' => $currentStudent, 'isStudentAllocated' => false]);
    }


    public function blogging(Request $request)
    {
        $pageTitle = 'Posts';

        $tutor = Auth::user();
        $tutorId = $tutor->id;

        // Initialize the base query
        $query = Post::with(['creator', 'receiver', 'documents', 'comments']);

        // Get the list of assigned students for the tutor
        $assignedStudentIds = User::whereHas('studentAllocations', function ($query) use ($tutorId) {
            $query->where('tutor_id', $tutorId)->where('active', 1);
        })->where('role_id', 1)->pluck('id')->toArray();

        // Filter by post type
        switch ($request->input('post_by')) {
            case 'myPosts':
                // Show only posts created by the tutor
                $query->where('post_create_by', $tutorId);

                // Optionally filter by a specific student if provided
                $studentId = $request->input('student_filter');
                if ($studentId) {
                    $query->where('post_received_by', $studentId);
                }
                break;

            case 'studentPosts':
                // Show posts created by the tutor's assigned students
                $studentId = $request->input('student_filter');
                if ($studentId && in_array($studentId, $assignedStudentIds)) {
                    // If a specific student is selected, show only their posts
                    $query->where('post_create_by', $studentId);
                } else {
                    // Otherwise, show posts from all assigned students
                    $query->whereIn('post_create_by', $assignedStudentIds);
                }
                break;

            default:
                // Default case: Show posts created by the tutor AND their assigned students
                $query->where(function ($q) use ($tutorId, $assignedStudentIds) {
                    $q->where('post_create_by', $tutorId)
                        ->orWhereIn('post_create_by', $assignedStudentIds);
                });

                // Additional logic: If a specific student is selected, show posts received by or created by that student
                $studentId = $request->input('student_filter');
                if ($studentId && in_array($studentId, $assignedStudentIds)) {
                    $query->where(function ($q) use ($studentId) {
                        $q->where('post_received_by', $studentId)
                            ->orWhere('post_create_by', $studentId);
                    });
                }
                break;
        }

        // Apply search filter
        $searchPost = $request->input('search_post');
        if ($searchPost) {
            $query->where(function ($q) use ($searchPost) {
                $q->where('post_title', 'like', '%' . $searchPost . '%');
            });
        }

        // Exclude deleted posts and order by updated_at
        $posts = $query->where('post_status', '!=', 'deleted')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Fetch the list of students assigned to the tutor
        $students = User::whereHas('studentAllocations', function ($query) use ($tutorId) {
            $query->where('tutor_id', $tutorId)->where('active', 1);
        })->where('role_id', 1)->get();

        // Return the view with the filtered posts and students
        return view('tutor.blogging', compact(['pageTitle', 'posts', 'students', 'tutor']));
    }


    public function createpost(Request $request)
    {
        $pageTitle = "Create Post";
        $tutor = Auth::user();
        $tutorId = $tutor->id;
        $students = $query = User::whereHas('studentAllocations', function ($query) use ($tutorId) {
            $query->where('tutor_id', $tutorId)->where('active', 1);
        })->where('role_id', 1)->get();
        return view('tutor.createpost', compact(['pageTitle', 'tutor', 'students']));
    }

    public function savepost(Request $request)
    {
        $request->validate([
            'selected_student' => 'required',
            'post_title' => 'required|string|max:255',
            'post_desc' => 'nullable|string|max:500',
            'post_files' => ['nullable', 'array', 'max:20480'],  // 'array' for multiple files
            'post_files.*' => 'mimes:pdf,docx,xlsx,jpeg,jpg,png,zip|max:20480',
        ], [
            'selected_students.required' => 'Please select at least one student.',
            'post_title.required' => 'The post title field is required.',
            'post_title.string' => 'The post title must be a string.',
            'post_title.max' => 'The post title may not be greater than 255 characters.',

            'post_desc.string' => 'The post description must be a string.',
            'post_desc.max' => 'The post description may not be greater than 500 characters.',

            'post_files.array' => 'The uploaded files must be an array.',
            'post_files.max' => 'Total file size must not exceed 20MB.',

            'post_files.*.mimes' => 'Each uploaded file must be a PDF, DOCX, XLSX, JPEG, JPG, or PNG.',
            'post_files.*.max' => 'Each uploaded file must not exceed 20MB.',
        ]);
        $post = new Post();
        $post->post_title = $request->post_title;
        $post->post_description = $request->post_desc;
        $post->post_status = 'new';
        $post->post_create_by = $request->create_by;
        $post->post_received_by = $request->selected_student;
        $post->save();
        if ($request->hasFile('post_files')) {
            try {
                foreach ($request->file('post_files') as $file) {
                    $document = new Document();
                    $path = 'private/student_files/';
                    if (!$path) {
                        mkdir($path);
                    }
                    $fileName = $file->getClientOriginalName();
                    $fileSize = $file->getSize();
                    // Store or process the file
                    $file->move($path, $fileName);

                    $document->doc_name = $fileName;
                    $document->doc_file_path = $path . $fileName;
                    $document->doc_size = $fileSize;
                    $document->post_id = $post->id;
                    $document->save();
                }
            } catch (\Exception $exp) {
                return view('tutor.createpost', [
                    'error' => 'File upload failed: ' . $exp->getMessage()
                ]);
            }
        }
        return redirect()->route('tutor.blogging')->with('success', 'Post upload success!');
    }

    public function editpost($id)
    {
        $pageTitle = "Update";
        $post = Post::findOrFail($id);
        $tutor = Auth::user();
        // $post = $post->with('creator','receiver','document','comment');
        if ($post->post_create_by != Auth::user()->id) {
            return redirect()->back()->withErrors(['warning' => 'You do not have access to edit this post.']);
        }
        return view('tutor.updatepost', compact(['post', 'tutor']));
    }

    public function updatepost(Request $request, $id)
    {
        $request->validate([
            'update_title' => 'required|string|max:255',
            'update_desc' => 'nullable|string|max:500',
            'post_files_upload' => ['nullable', 'array', 'max:20480'],  // 'array' for multiple files
            'post_files_upload.*' => 'mimes:pdf,docx,xlsx,jpeg,jpg,png,zip|max:20480',
        ], [
            'update_title.required' => 'The post title field is required.',
            'update_title.string' => 'The post title must be a string.',
            'update_title.max' => 'The post title may not be greater than 255 characters.',

            'update_desc.string' => 'The post description must be a string.',
            'update_desc.max' => 'The post description may not be greater than 500 characters.',

            'post_files_upload.array' => 'The uploaded files must be an array.',
            'post_files_upload.max' => 'Total file size must not exceed 20MB.',

            'post_files_upload.*.mimes' => 'Each uploaded file must be a PDF, DOCX, XLSX, JPEG, JPG, or PNG.',
            'post_files_upload.*.max' => 'Each uploaded file must not exceed 20MB.',
        ]);
        $post = Post::findOrFail($id);
        $post->post_title = $request->update_title;
        $post->post_description = $request->update_desc;
        $post->post_status = 'updated';
        $post->save();
        \Log::info("Removed Documents: " . json_encode($request->input('removed_documents')));
        // dd($request->input('removed_documents'));
        if ($request->has('removed_documents') && !empty($request->removed_documents)) {
            $removedDocumentIds = json_decode($request->removed_documents, true);
            //dd($removedDocumentIds);
            if (is_array($removedDocumentIds)) {
                foreach ($removedDocumentIds as $docId) {
                    $document = Document::find($docId);
                    if ($document) {
                        $filePath = $document->doc_file_path; // Get full file path

                        // Delete the file from the storage directory
                        if (file_exists($filePath)) {
                            unlink($filePath); // Delete file
                            \Log::info("Deleted file: " . $filePath);
                        } else {
                            \Log::warning("File not found: " . $filePath);
                        }
                    }
                    Document::where('id', $docId)->delete();
                    //\Log::info('doc delete ' . $docId); // Log the post ID

                }
            }
        }
        if ($request->hasFile('post_files_upload')) {
            try {
                foreach ($request->file('post_files_upload') as $file) {
                    $document = new Document();
                    $path = 'private/student_files/';
                    if (!$path) {
                        mkdir($path);
                    }
                    $fileName = $file->getClientOriginalName();
                    $fileSize = $file->getSize();
                    // Store or process the file
                    $file->move($path, $fileName);

                    $document->doc_name = $fileName;
                    $document->doc_file_path = $path . $fileName;
                    $document->doc_size = $fileSize;
                    $document->post_id = $post->id;
                    $document->save();
                }
            } catch (\Exception $exp) {
                return view('tutor.updatepost', [
                    'error' => 'File upload failed: ' . $exp->getMessage()
                ]);
            }
        }
        return to_route('tutor.blogging')->with('success', 'Post is successfully updated.');
    }

    public function deletepost(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        if (!$post) {
            return back()->withErrors('warning', 'Post not found.');
        }
        // dd(Auth::user()->id);
        if (Auth::user()->id) {
            $post->post_status = 'deleted';
            $post->save();
            return redirect()->route('tutor.blogging')->with('success', 'Your post is deleted!');
        }
        // $meeting->delete();
        // return back()->withErrors('warning', 'Delete access denied.');
        $notify[] = ['Delete access denied.'];
        return back()->withErrors($notify);
    }

    public function postcomment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required',
        ]);
        \Log::info('Received comment submission for post ID: ' . $id); // Log the post ID
        \Log::info('Comment data: ' . $request->comment); // Log the comment text
        $comment = new Comment();
        $comment->text = $request->comment;
        $comment->post_id = $id;
        // dd($id);
        $comment->user_id = Auth::user()->id;

        $comment->save();

        return redirect()->route('tutor.blogging')->with('success', 'Comment upload success!');
    }
    public function editcomment(Request $request)
    {
        $comment = Comment::find($request->id);

        $request->validate([
            'comment_update' => 'required',
        ]);
        $comment->text = $request->comment_update;
        $comment->save();
        return redirect()->route('tutor.blogging')->with('success', 'Comment update success!');
    }
    public function deleteComment($id)
    {
        $comment = Comment::findOrFail($id);

        if (!$comment) {
            return redirect()->back()->with('error', 'Comment not found.');
        }
        $comment->delete();
        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }


    public function report(Request $request)
    {
        $tutorId = auth()->id();

        // Get current month if not provided
        $currentMonth = $request->input('month', Carbon::now()->month);

        // Get student name filter if provided
        $studentName = $request->input('student_name');

        $studentsDropDown = Allocation::where('tutor_id', $tutorId)
            ->where('active', 1)
            ->with('student') // Assuming you have a relationship
            ->get();
        // Retrieve students assigned to the tutor
        $students = DB::table('allocation')
            ->join('users', 'allocation.student_id', '=', 'users.id')
            ->where('allocation.tutor_id', $tutorId)
            ->where('allocation.active', 1)
            ->where('users.role_id', 1);

        // Apply student name filter
        if (!empty($request->student_id)) {
            $students->where('users.id', $request->student_id);
        }

        // Fetch student activity counts
        $studentReports = $students
            ->leftJoin('post as posts', function ($join) use ($currentMonth) {
                $join->on('posts.post_create_by', '=', 'users.id')
                    ->whereMonth('posts.created_at', $currentMonth);
            })
            ->leftJoin('comment as comments', function ($join) use ($currentMonth) {
                $join->on('comments.user_id', '=', 'users.id')
                    ->whereMonth('comments.created_at', $currentMonth);
            })
            ->leftJoin('document as documents', function ($join) {
                $join->on('documents.post_id', '=', 'posts.id'); // Documents linked to posts
            })
            ->leftJoin('meeting_schedule as meeting_schedules', function ($join) use ($currentMonth) {
                $join->on('meeting_schedules.student_id', '=', 'users.id')
                    ->whereMonth('meeting_schedules.meeting_date', $currentMonth);
            })
            ->select(
                'users.id as student_id',
                'users.user_code',
                'users.first_name',
                'users.last_name',
                DB::raw('COUNT(DISTINCT posts.id) as posts'),
                DB::raw('COUNT(DISTINCT comments.id) as comments'),
                DB::raw('COUNT(DISTINCT documents.id) as documents'),
                DB::raw('COUNT(DISTINCT meeting_schedules.id) as meetings')
            )
            ->groupBy('users.id', 'users.user_code', 'users.first_name', 'users.last_name')
            ->get();


        return view('tutor.report', compact('studentReports', 'currentMonth', 'studentName', 'studentsDropDown'));
    }
    protected function getStudentActivities($studentId, $year)
    {
        return [
            'posts' => DB::table('post')
                ->selectRaw("MONTH(created_at) as month, COUNT(*) as count")
                ->where('post_create_by', $studentId)
                ->where('is_meeting', 0)
                ->whereYear('created_at', $year)
                ->groupBy('month')
                ->pluck('count', 'month'),

            'comments' => DB::table('comment')
                ->selectRaw("MONTH(created_at) as month, COUNT(*) as count")
                ->where('user_id', $studentId)
                ->whereYear('created_at', $year)
                ->groupBy('month')
                ->pluck('count', 'month'),

            'documents' => DB::table('document')
                ->join('post', 'document.post_id', '=', 'post.id')
                ->selectRaw("MONTH(document.created_at) as month, COUNT(*) as count")
                ->where('post.post_create_by', $studentId)
                ->whereYear('document.created_at', $year)
                ->groupBy('month')
                ->pluck('count', 'month'),

            'meetings' => DB::table('meeting_schedule')
                ->selectRaw("MONTH(meeting_date) as month, COUNT(*) as count")
                ->where('student_id', $studentId)
                ->whereYear('updated_at', $year)
                ->groupBy('month')
                ->pluck('count', 'month')
        ];
    }


    //update or create function
    public function save(Request $request, $id = null)
    {
        $request->validate([
            'meeting_title' => 'required|string|max:255',
            'meeting_description' => 'nullable|string|max:500',
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
                'meeting_status' => "New",
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
                'meeting_status' => "new",
                'student_id' => $request->student_id,
                'tutor_id' => Auth::id(),
                'meeting_location' => $request->meeting_type === 'real' ? $request->meeting_location : null,
                'meeting_platform' => $request->meeting_type === 'virtual' ? $request->meeting_platform : null,
                'meeting_link' => $request->meeting_type === 'virtual' ? $request->meeting_link : null,
            ]);
            $post = Post::create([
                'post_create_by' => Auth::id(),
                'post_received_by' => $request->student_id,
                'post_title' => $request->meeting_title,
                'post_status' => "new",
                'post_description' => $request->meeting_description,
                'is_meeting' =>  1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('tutor.meetinglists')->with('success', 'Meeting created!');
        }
    }
    public function toggleStatus($id)
    {
        $meeting = MeetingSchedule::findOrFail($id);

        // Toggle status between "completed" and "new"
        $meeting->meeting_status = $meeting->meeting_status === 'completed' ? 'new' : 'completed';
        $meeting->save();
        return redirect()->route('tutor.meetinglists')->with('success', 'Meeting status updated successfully!')->header('Cache-Control', 'no-store');
        //  return redirect()->back()->with('success', 'Meeting status updated successfully!');
    }
    public function cancelMeeting(Request $request)
    {
        $meeting = MeetingSchedule::findOrFail($request->id);
        $meeting->meeting_status = 'cancelled';
        $meeting->save();
        // $meeting->delete();

        return redirect()->route('tutor.meetinglists')->with('success', 'Meeting is cancelled!');
    }
    //meeting code end

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
