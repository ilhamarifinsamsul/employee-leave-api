<?php

use App\Http\Controllers\LeaveController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// route get list leave
Route::get('/leave', [LeaveController::class, 'index'])->name('leave.index');
// route create leave
Route::get('/leave/create', [LeaveController::class, 'create'])->name('leave.create');
// route store leave
Route::post('/leave', [LeaveController::class, 'store'])->name('leave.store');

// route patch approve
Route::patch('/leave/{leaveRequest}/approve', [LeaveController::class, 'approve'])->name('leave.approve');
// route patch reject
Route::patch('/leave/{leaveRequest}/reject', [LeaveController::class, 'reject'])->name('leave.reject');
