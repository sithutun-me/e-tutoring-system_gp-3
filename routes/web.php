<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AllocationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Student\PostController;
use App\Http\Controllers\TutorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest', 'protect_auth'])->group(function () {

    Route::get('/', function () {
        return view('login');
    });

    Route::view('/login', 'login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth', 'protect_auth'])->group(function () {

    // Admin Dashboard Route
    Route::middleware(['role:3'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        Route::get('/admin/assignedlists', [AdminController::class, 'assignedlists'])->name('admin.assignedlists');
        Route::get('/admin/reallocation', [AdminController::class, 'reallocation'])->name('admin.reallocation');
        Route::get('/admin/tutorlists', [AdminController::class, 'tutorlists'])->name('admin.tutorlists');
        Route::get('/admin/studentlists', [AdminController::class, 'studentlists'])->name('admin.studentlists');
        Route::get('/admin/student/dashboard/{id}', [StudentController::class, 'index'])->name('admin.student.dashboard');
        Route::get('/admin/tutor/dashboard/{id}', [TutorController::class, 'index'])->name('admin.tutor.dashboard');

        Route::get('/admin/allocation/{id?}', [AllocationController::class, 'index'])->name('admin.allocation');
        Route::post('/admin/allocate', [AllocationController::class, 'allocate'])->name('admin.allocate');
        Route::get('/admin/assignedlists', [AllocationController::class, 'assignedLists'])->name('admin.assignedlists');

        Route::get('/admin/reallocation', [AllocationController::class, 'reallocation'])->name('admin.reallocation');
        Route::post('/admin/reallocate', [AllocationController::class, 'reallocate'])->name('admin.reallocate');

        Route::post('/admin/allocation/delete', [AllocationController::class, 'deleteAllocation'])->name('admin.allocation.delete');

        Route::get('/admin/students/filter', [AllocationController::class, 'filterStudents'])->name('admin.students.filter');
        Route::get('/admin/allocations/filter', [AllocationController::class, 'filterAllocations'])->name('admin.allocations.filter');

        Route::get('/admin/report', [AdminController::class, 'report'])->name('admin.report');
    });

    // Tutor Dashboard Route
    Route::middleware(['role:2'])->group(function () {
        Route::get('/tutor/dashboard', [TutorController::class, 'index'])->name('tutor.dashboard');
        Route::get('/tuotr/dashboard', [TutorController::class, 'interactionCounts'])->name('tutor.interactions');

        Route::get('/tutor/meetinglists', [TutorController::class, 'meetinglists'])->name('tutor.meetinglists');

        //show create form
        Route::get('/tutor/meetingdetail', [TutorController::class, 'meetingdetail'])->name('tutor.meetingdetail.create');
        //show edit form
        Route::get('/tutor/meetingdetail/{id}/edit', [TutorController::class, 'meetingdetail'])->name('tutor.meetingdetail.update');
        //view meeting detail
        Route::get('/tutor/meetingdetail/{id}', [TutorController::class, 'meetingview'])->name('tutor.meetingdetail.view');

        //crud posts
        Route::get('/tutor/createpost', [TutorController::class, 'createpost'])->name('tutor.createpost');
        Route::post('/tutor/savepost', [TutorController::class, 'savepost'])->name('tutor.savepost');
        Route::get('/tutor/editpost/{id}', [TutorController::class, 'editpost'])->name('tutor.editpost');
        Route::post('/tutor/updatepost/{id}', [TutorController::class, 'updatepost'])->name('tutor.updatepost');
        Route::post('/tutor/deletepost/{id}', [TutorController::class, 'deletepost'])->name('tutor.deletepost');
        //view posts
        Route::get('/tutor/blogging', [TutorController::class, 'blogging'])->name('tutor.blogging');
        //comment
        Route::post('/tutor/postcomment/{id}', [TutorController::class, 'postcomment'])->name('tutor.postcomment');
        Route::post('/tutor/deletecomment/{id}', [TutorController::class, 'deletecomment'])->name('tutor.deletecomment');
        Route::post('/tutor/editcomment', [TutorController::class, 'editcomment'])->name('tutor.editcomment');

        //create meeting
        Route::post('/tutor/meetingdetail', [TutorController::class, 'save'])->name('tutor.save');


        //update meeting
        Route::put('/tutor/meetingdetail/{id}', [TutorController::class, 'save'])->name('tutor.update');

        //status toggle (completed/ new)
        Route::put('/tutor/meetingdetail/{id}/toggle-status', [TutorController::class, 'toggleStatus'])
            ->name('tutor.meetingdetail.toggleStatus');

        Route::post('/tutor/meetingdetail/cancel', [TutorController::class, 'cancelMeeting'])->name('tutor.meetingdetail.cancelmeeting');

        Route::get('/tutor/report', [TutorController::class, 'report'])->name('tutor.report');





        Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');


        Route::get('/student/meetinglists', [StudentController::class, 'meetinglists'])->name('student.meetinglists');
        //show create form
        Route::get('/student/meetingdetail', [StudentController::class, 'meetingdetail'])->name('student.meetingdetail.create');
        //show edit form
        Route::get('/student/meetingdetail/{id}/edit', [StudentController::class, 'meetingdetail'])->name('student.meetingdetail.update');
        //view meeting detail
        Route::get('/student/meetingdetail/{id}', [StudentController::class, 'meetingview'])->name('student.meetingdetail.view');

        Route::post('/student/meetingdetail', [StudentController::class, 'save'])->name('save');
        Route::put('/student/meetingdetail/{id}', [StudentController::class, 'save'])->name('update');

        //status toggle (completed/ new)
        Route::put('/student/meetingdetail/{id}/toggle-status', [StudentController::class, 'toggleStatus'])
            ->name('student.meetingdetail.toggleStatus');

        Route::post('/student/meetingdetail/cancel', [StudentController::class, 'cancelMeeting'])->name('student.meetingdetail.cancelmeeting');

        //view posts student
        Route::get('/student/blogging', [PostController::class, 'index'])->name('student.blogging');
        //crud posts student
        Route::get('/student/createpost', [PostController::class, 'createpost'])->name('student.createpost');
        Route::post('/student/savepost', [PostController::class, 'savepost'])->name('student.savepost');
        Route::get('/student/editpost/{id}', [PostController::class, 'editpost'])->name('student.editpost');
        Route::post('/student/updatepost/{id}', [PostController::class, 'updatepost'])->name('student.updatepost');
        Route::post('/student/deletepost/{id}', [PostController::class, 'deletepost'])->name('student.deletepost');

        //comment
        Route::post('/student/postcomment/{id}', [PostController::class, 'postcomment'])->name('student.postcomment');
        Route::post('/student/deletecomment/{id}', [PostController::class, 'deletecomment'])->name('student.deletecomment');
        Route::post('/student/editcomment', [PostController::class, 'editcomment'])->name('student.editcomment');

        Route::get('/student/report', [StudentController::class, 'report'])->name('student.report');
    });

    // Student Dashboard Route
    Route::middleware(['role:1'])->group(function () {
        Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');


        Route::get('/student/meetinglists', [StudentController::class, 'meetinglists'])->name('student.meetinglists');
        //show create form
        Route::get('/student/meetingdetail', [StudentController::class, 'meetingdetail'])->name('student.meetingdetail.create');
        //show edit form
        Route::get('/student/meetingdetail/{id}/edit', [StudentController::class, 'meetingdetail'])->name('student.meetingdetail.update');
        //view meeting detail
        Route::get('/student/meetingdetail/{id}', [StudentController::class, 'meetingview'])->name('student.meetingdetail.view');

        Route::post('/student/meetingdetail', [StudentController::class, 'save'])->name('save');
        Route::put('/student/meetingdetail/{id}', [StudentController::class, 'save'])->name('update');

        //status toggle (completed/ new)
        Route::put('/student/meetingdetail/{id}/toggle-status', [StudentController::class, 'toggleStatus'])
            ->name('student.meetingdetail.toggleStatus');

        Route::post('/student/meetingdetail/cancel', [StudentController::class, 'cancelMeeting'])->name('student.meetingdetail.cancelmeeting');

        //view posts student
        Route::get('/student/blogging', [PostController::class, 'index'])->name('student.blogging');
        //crud posts student
        Route::get('/student/createpost', [PostController::class, 'createpost'])->name('student.createpost');
        Route::post('/student/savepost', [PostController::class, 'savepost'])->name('student.savepost');
        Route::get('/student/editpost/{id}', [PostController::class, 'editpost'])->name('student.editpost');
        Route::post('/student/updatepost/{id}', [PostController::class, 'updatepost'])->name('student.updatepost');
        Route::post('/student/deletepost/{id}', [PostController::class, 'deletepost'])->name('student.deletepost');

        //comment
        Route::post('/student/postcomment/{id}', [PostController::class, 'postcomment'])->name('student.postcomment');
        Route::post('/student/deletecomment/{id}', [PostController::class, 'deletecomment'])->name('student.deletecomment');
        Route::post('/student/editcomment', [PostController::class, 'editcomment'])->name('student.editcomment');

        Route::get('/student/report', [StudentController::class, 'report'])->name('student.report');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
//admin dashboard chart
Route::get('/student-inactivity', [AdminController::class, 'getInactiveStudentsData']);
Route::get('/average_messages', [AdminController::class, 'getAverageMessage']);
Route::get('/browser-chart', [AdminController::class, 'getBrowserPieData']);

Route::get('/student_list_with_assigned_tutors', [AdminController::class, 'getStudentListWithAssignedTutors']);

//tutor dashboard chart
Route::get('/tutor_student_interaction_dashboard/{id}', [TutorController::class, 'interactionCounts']);

//student dashboard chart
Route::get('/meeting_counts/{id}', [StudentController::class, 'getMeetingPieData']);
Route::get('/myActivities/{id}', [StudentController::class, 'myActivities']);
Route::get('/tutorActivities/{id}', [StudentController::class, 'tutorActivities']);


Route::post('tutor/blogging/{id}/comment', [TutorController::class, 'postcomment'])->name('tutor.postcomment');



Route::fallback(function () {
    abort(404);
});
