@extends('layouts.app');
@section('title', 'List Leaves')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-0">Daftar Pengajuan Cuti</h4>
        <small class="text-muted">List cuti semua karyawan</small>
    </div>

    <a href="{{ route('leave.create') }}" class="btn btn-primary">
        + Ajukan Cuti
    </a>
</div>

{{-- Alert sukses --}}
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Department</th>
                        <th>Tanggal</th>
                        <th>Total Hari</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaveRequests as $leaveRequest)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $leaveRequest->employee->nik }}</td>
                        <td>{{ $leaveRequest->employee->name }}</td>
                        <td>{{ $leaveRequest->employee->department }}</td>
                        <td>
                            {{ $leaveRequest->start_date }}
                            <span class="text-muted">s/d</span>
                            {{ $leaveRequest->end_date }}
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{ $leaveRequest->total_days }} hari
                            </span>
                        </td>
                        {{-- status --}}
                        <td>
                            @if ($leaveRequest->status === 'pending')
                                <span class="badge bg-warning">
                                    {{ $leaveRequest->status }}
                                </span>
                            @elseif ($leaveRequest->status === 'approved')
                                <span class="badge bg-success">
                                    {{ $leaveRequest->status }}
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    {{ $leaveRequest->status }}
                                </span>
                            @endif
                        </td>
                        {{-- Aksi Admin --}}
                        <td>
                            
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection
