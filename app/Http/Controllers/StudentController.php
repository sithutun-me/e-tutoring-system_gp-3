<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return view('student.dashboard');
    }
    public function meetinglists()
    {
        return view('student.meetinglists');
    }
    public function meetingdetail()
    {
        return view('student.meetingdetail');
    }
}
