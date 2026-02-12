<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LeaveController extends Controller
{
    private string $apiUrl;

    // __construct
    public function __construct()
    {
        $this->apiUrl = 'http://127.0.0.1:8001/api/leave-requests';
    }

    // List leave page
    public function index()
    {
        // response ambil Http
        $response = Http::acceptJson()->get($this->apiUrl);

        if ($response->failed()) {
            return view('leaves.index', [
                'data' => [],
                'error' => $response->json('message') ?? 'Gagal mengambil data dari API'
            ]);
        }

        // hasil API kamu bentuknya: { success, message, data }
        $data = json_decode($response->getbody()->getContents(), true);

        $leaveRequests = $data['data'] ?? [];

        return view('leaves.index', compact('leaveRequests'));
    }

    // Form create
    public function create()
    {
        $employees = Employee::all();
        return view('leaves.create', compact('employees'));
    }

    // Store leave (POST ke API)
    public function store(Request $request)
    {
        $response = Http::acceptJson()->post($this->apiUrl, [
            'employee_id' => $request->employee_id,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
        ]);

        // kalau error validasi / 422
        if ($response->status() === 422) {
            return back()
                ->withErrors($response->json('errors') ?? ['error' => $response->json('message')])
                ->withInput();
        }

        if ($response->failed()) {
            return back()
                ->withErrors(['error' => $response->json('message') ?? 'Request gagal'])
                ->withInput();
        }

        return redirect()->route('leave.index')->with('success', 'Pengajuan cuti berhasil dibuat.');
    }

    // Approve
    public function approve(LeaveRequest $leaveRequest)
    {
        $response = Http::acceptJson()
            ->patch($this->apiUrl . '/' . $leaveRequest->id . '/approve');

        if ($response->failed()) {
            return back()->withErrors([
                'error' => $response->json('message') ?? 'Approve gagal'
            ]);
        }

        return redirect()->route('leave.index')->with('success', $response->json('message'));
    }

    // Reject
    public function reject(LeaveRequest $leaveRequest)
    {
        $response = Http::acceptJson()
            ->patch($this->apiUrl . '/' . $leaveRequest->id . '/reject');

        if ($response->failed()) {
            return back()->withErrors([
                'error' => $response->json('message') ?? 'Reject gagal'
            ]);
        }

        return redirect()->route('leave.index')->with('success', $response->json('message'));
    }
}
