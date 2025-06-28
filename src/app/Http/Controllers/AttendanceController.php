<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function create(){
        return view('staff.working_status');
    }

    public function store(){

    }

    public function index(){
        return view('admin.attendance_list');
    }
}
