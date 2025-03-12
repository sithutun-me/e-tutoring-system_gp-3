<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AllocationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TutorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function(){

    Route::get('/', function () {
        return view('welcome');
    });

    Route::view('/login', 'login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);

});

Route::middleware('auth')->group(function(){

    // Admin Dashboard Route
    Route::middleware(['role:3'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        Route::get('/admin/assignedlists', [AdminController::class, 'assignedlists'])->name('admin.assignedlists');
        Route::get('/admin/reallocation', [AdminController::class, 'reallocation'])->name('admin.reallocation');
        Route::get('/admin/tutorlists', [AdminController::class, 'tutorlists'])->name('admin.tutorlists');
        Route::get('/admin/studentlists', [AdminController::class, 'studentlists'])->name('admin.studentlists');
        Route::get('/admin/student/dashboard', [StudentController::class, 'index'])->name('admin.student.dashboard');
        Route::get('/admin/tutor/dashboard', [TutorController::class, 'index'])->name('admin.tutor.dashboard');


        Route::get('/admin/allocation', [AllocationController::class, 'index'])->name('admin.allocation');
        Route::post('/admin/allocate', [AllocationController::class, 'allocate'])->name('admin.allocate');
        Route::get('/admin/assignedlists', [AllocationController::class, 'assignedLists'])->name('admin.assignedlists');

        Route::get('/admin/reallocation', [AllocationController::class, 'reallocation'])->name('admin.reallocation');
        Route::post('/admin/reallocate', [AllocationController::class, 'reallocate'])->name('admin.reallocate');

        Route::post('/admin/allocation/delete', [AllocationController::class, 'deleteAllocation'])->name('admin.allocation.delete');
    });

    // Tutor Dashboard Route
    Route::middleware(['role:2'])->group(function () {
        Route::get('/tutor/dashboard', [TutorController::class, 'index'])->name('tutor.dashboard');

        
        Route::get('/tutor/meetinglists', [TutorController::class, 'meetinglists'])->name('tutor.meetinglists');
        
        //show create form
        Route::get('/tutor/meetingdetail', [TutorController::class, 'meetingdetail'])->name('tutor.meetingdetail.create');
        //show edit form
        Route::get('/tutor/meetingdetail/{id}/edit', [TutorController::class, 'meetingdetail'])->name('tutor.meetingdetail.update');
        //view meeting detail
        Route::get('/tutor/meetingdetail/{id}', [TutorController::class, 'meetingview'])->name('tutor.meetingdetail.view');
        
        //create meeting
        Route::post('/tutor/meetingdetail', [TutorController::class, 'save'])->name('save');
        
        //update meeting
        Route::put('/tutor/meetingdetail/{id}', [TutorController::class, 'save'])->name('update');

        //status toggle (completed/ new)
        Route::put('/tutor/meetingdetail/{id}/toggle-status', [TutorController::class, 'toggleStatus'])
        ->name('tutor.meetingdetail.toggleStatus');

        Route::post('/tutor/meetingdetail/cancel', [TutorController::class, 'cancelMeeting'])->name('tutor.meetingdetail.cancelmeeting');

    });

    // Student Dashboard Route
    Route::middleware(['role:1'])->group(function () {
        Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



});

Route::get('/student-inactivity', [AdminController::class, 'getInactiveStudentsData']);
Route::get('/average_messages', [AdminController::class, 'getAverageMessage']);
Route::get('/student_list_with_assigned_tutors', [AdminController::class, 'getStudentListWithAssignedTutors']);