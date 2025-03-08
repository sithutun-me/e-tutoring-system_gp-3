<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TutorController extends Controller
{
    public function index()
    {
        return view('tutor.dashboard');
    }
    public function meetinglists()
    {
        return view('tutor.meetinglists');
    }
    public function meetingdetail($id = null) {
        if($id) {
            // $resource = Resource::findOrFail($id);
            return view('tutor.meetingdetail', compact('id'));
        }
        // For create (no ID), just pass null or empty data
        return view('tutor.meetingdetail', ['id' => null]);

    }
    public function meetingview($id = null) {
        if($id) {
            // $resource = Resource::findOrFail($id);
            return view('tutor.meetingdetail', compact('id'));
        }
        // For create (no ID), just pass null or empty data
        return view('tutor.meetingdetail', ['id' => null]);

    }
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
