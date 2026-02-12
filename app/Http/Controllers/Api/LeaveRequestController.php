<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeaveRequestController extends Controller
{
    // List leave requests by employee
    public function index(Request $request)
    {
        $data = LeaveRequest::with('employee')->latest();

        if ($request->employee_id) {
            $employee = Employee::find($request->employee_id);
            // jika employee tidak ditemukan
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data karyawan tidak ditemukan',
                ], 404);
            }

            $data->where('employee_id', $employee->id);
        }

        return response()->json([
            'data' => $data->get(),
            'success' => true,
            'message' => 'Data cuti berhasil diambil'
        ], 200);
    }

    // endpoint pengajuan cuti
    public function store(Request $request)
    {
        // validasi input
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data cuti gagal disimpan',
                'errors' => $validator->errors(),
            ], 422);
        }
        // jika validasi berhasil
        $employee = Employee::find($request->employee_id);

        // hitung sisa cuti tidak boleh 0
        if ($employee->leave_balance <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Sisa cuti tidak boleh 0',
            ], 422);
        }

        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);

        // hitung jumlah hari cuti termasuk awal & akhir
        $totalDays = $start_date->diffInDays($end_date) + 1;

        // pengajuan cuti tidak boleh lebih dari 12 hari
        if ($totalDays > 12) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan cuti tidak boleh lebih dari 12 hari',
            ], 422);
        }

        // tidak boleh melebihi sisa cuti
        if ($totalDays > $employee->leave_balance) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah hari cuti tidak boleh melebihi sisa cuti',
                'leave_balance' => $employee->leave_balance,
                'total_days' => $totalDays,
            ], 422);
        }

        // simpan data cuti
        $leaveRequest = LeaveRequest::create([
            'employee_id' => $employee->id,
            'start_date' => $start_date->toDateString(),
            'end_date' => $end_date->toDateString(),
            'total_days' => $totalDays,
            'status' => 'pending',

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data cuti berhasil disimpan',
            'data' => $leaveRequest,
        ], 200);
    }

    // endpoint approve leave methode binding
    public function approve(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Request sudah diproses sebelumnya',
            ], 422);
        }

        $employee = $leaveRequest->employee;

        // mengurangi sisa cuti
        if ($leaveRequest->total_days > $employee->leave_balance) {
            return response()->json([
                'success' => false,
                'message' => 'Sisa cuti tidak cukup untuk diapprove',

            ], 422);
        }

        $employee->decrement('leave_balance', $leaveRequest->total_days);

        // update status cuti
        $leaveRequest->update([
            'status' => 'approved',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data cuti berhasil diapprove',
            'data' => $leaveRequest->fresh(),
        ], 200);
    }

    // endpoint reject leave methode binding
    public function reject(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Request sudah diproses sebelumnya.'
            ], 422);
        }

        $leaveRequest->update([
            'status' => 'rejected'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cuti berhasil di-reject.',
            'data' => $leaveRequest->fresh()
        ]);
    }

}
