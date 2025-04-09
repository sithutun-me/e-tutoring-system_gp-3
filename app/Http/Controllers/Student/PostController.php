<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Allocation;
use App\Models\Comment;
use App\Models\Document;
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
        $studentId = $student->id;

        // Get the assigned tutor for the student
        $tutor = User::whereHas('tutorAllocations', function ($query) use ($studentId) {
            $query->where('student_id', $studentId)->where('active', 1);
        })->where('role_id', 2)->first(); // Assuming tutors have role_id = 2

        if (!$tutor) {
            // If no tutor is assigned, we can handle this gracefully
            return redirect()->back()->with('error', 'You do not have an active tutor assigned.');
        }

        $tutorId = $tutor->id;

        // Initialize the base query
        $query = Post::with(['creator', 'receiver', 'documents', 'comments']);

        // Filter by post type
        switch ($request->input('post_by')) {
            case 'myPosts':
                // Show only posts created by the student
                $query->where('post_create_by', $studentId);
                break;

            case 'tutorPosts':
                // Show only posts created by the assigned tutor
                $query->where('post_create_by', $tutorId)
                ->where('post_received_by', $studentId);
                break;

            default:
                // Default case: Show posts created by the student AND their tutor
                $query->where(function ($q) use ($studentId, $tutorId) {
                    $q->where('post_create_by', $studentId)
                        ->orWhere('post_create_by', $tutorId)
                        ->where('post_received_by', $studentId);;
                });
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

        // Return the view with the filtered posts and student data
        return view('student.blogging', compact(['pageTitle', 'posts', 'student']));
    }

    public function createpost()
    {
        $pageTitle = "Create Post";
        $student = Auth::user();
        $studentId = $student->id;
        $tutor = User::whereHas('tutorAllocations', function ($query) use ($studentId) {
            $query->where('student_id', $studentId)->where('active', 1);
        })->where('role_id', 2)->first();
        // dd($tutor);
        return view('student.createpost', compact(['pageTitle', 'tutor', 'student']));
    }


    public function savepost(Request $request)
    {
        $request->validate([
            'received_by' => 'required',
            'create_by' => 'required',
            'post_title' => 'required|string|max:255',
            'post_desc' => 'nullable|string|max:500',
            'post_files' => ['nullable', 'array', 'max:20480'],  // 'array' for multiple files
            'post_files.*' => 'mimes:pdf,docx,xlsx,jpeg,jpg,png,zip|max:20480',
        ], [
            'received_by.required' => 'You do not have assigned tutor.',
            'create_by.required' => 'You can not create post.',
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
        $post->post_received_by = $request->received_by;
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
                return view('student.createpost', [
                    'error' => 'File upload failed: ' . $exp->getMessage()
                ]);
            }
        }
        return redirect()->route('student.blogging')->with('success', 'Post upload success!');
    }

    public function editpost($id)
    {
        $pageTitle = "Update";
        $post = Post::findOrFail($id);
        $student = Auth::user();
        // $post = $post->with('creator','receiver','document','comment');
        if ($post->post_create_by != $student->id) {
            return redirect()->back()->withErrors(['warning' => 'You do not have access to edit this post.']);
        }
        return view('student.updatepost', compact(['post', 'student']));
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
                return view('student.updatepost', [
                    'error' => 'File upload failed: ' . $exp->getMessage()
                ]);
            }
        }
        return to_route('student.blogging')->with('success', 'Post is successfully updated.');
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
            return redirect()->route('student.blogging')->with('success', 'Your post is deleted!');
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

        return redirect()->route('student.blogging')->with('success', 'Comment upload success!');
    }
    public function editcomment(Request $request)
    {
        $comment = Comment::find($request->id);

        $request->validate([
            'comment_update' => 'required',
        ]);
        $comment->text = $request->comment_update;
        $comment->save();
        return redirect()->route('student.blogging')->with('success', 'Comment update success!');
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
}
