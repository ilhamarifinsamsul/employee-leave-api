<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveController extends Controller
{

    // List leave page
    public function index()
    {
        return view('leaves.index');
    }

    // Form create
    public function create()
    {
        return view('leaves.create');
    }

}
