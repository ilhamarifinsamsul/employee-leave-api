<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    // index
    public function index()
    {
        $leaveRequests = LeaveRequest::with('employee')->latest()->get();

        return view('leaves.index', compact('leaveRequests'));
    }


}
