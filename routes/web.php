<?php

use App\Http\Controllers\AdminController;
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
        
        Route::get('/admin/allocation', [AdminController::class, 'allocation'])->name('admin.allocation');
        Route::get('/admin/assignedlists', [AdminController::class, 'assignedlists'])->name('admin.assignedlists');
        Route::get('/admin/reallocation', [AdminController::class, 'reallocation'])->name('admin.reallocation');
        Route::get('/admin/tutorlists', [AdminController::class, 'tutorlists'])->name('admin.tutorlists');
        Route::get('/admin/studentlists', [AdminController::class, 'studentlists'])->name('admin.studentlists');
        Route::get('/admin/student/dashboard', [StudentController::class, 'index'])->name('admin.student.dashboard');
        Route::get('/admin/tutor/dashboard', [TutorController::class, 'index'])->name('admin.tutor.dashboard');

    });

    // Tutor Dashboard Route
    Route::middleware(['role:2'])->group(function () {
        Route::get('/tutor/dashboard', [TutorController::class, 'index'])->name('tutor.dashboard');
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


