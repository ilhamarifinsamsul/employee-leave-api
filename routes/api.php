<?php

use App\Http\Controllers\Api\LeaveRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('leave-requests')->group(function () {
    // list semua cuti
    Route::get('/', [LeaveRequestController::class, 'index']);

    // endpoint pengajuan cuti
    Route::post('/', [LeaveRequestController::class, 'store']);

    // endpoint untuk mengambil data employees
    Route::get('/employees', [LeaveRequestController::class, 'getEmployees']);

    // approve cuti
    Route::patch('/{leaveRequest}/approve', [LeaveRequestController::class, 'approve']);

    // reject cuti
    Route::patch('/{leaveRequest}/reject', [LeaveRequestController::class, 'reject']);
});


