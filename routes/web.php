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
