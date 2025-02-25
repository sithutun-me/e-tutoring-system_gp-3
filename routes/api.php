<?php
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;


// API Route
Route::get('/student-inactivity', [AdminController::class, 'getInactiveStudentsData']);
