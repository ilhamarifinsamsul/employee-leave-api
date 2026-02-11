@extends('layouts.app')
@section('title', 'Leaves Request')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold mb-0">Ajukan Cuti</h4>
                </div>
            </div>
            <div class="card-body p-4">
                <h4 class="fw-bold mb-3">Form Pengajuan Cuti</h4>
                {{-- Alert sukses --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Alert error --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('leave.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- Employee --}}
                        <div class="form-group">
                            <label for="employee_id" class="form-title">Employee</label>
                            <select class="form-control @error('employee_id') is-invalid @enderror" id="employee_id"
                                name="employee_id">
                                <option value="">Select Employee</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}  "
                                        {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- start date --}}
                        <div class="form-group">
                            <label for="start_date" class="form-title">Start Date</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                id="start_date" name="start_date" value="{{ old('start_date') }}">
                        </div>
                        {{-- end date --}}
                        <div class="form-group">
                            <label for="end_date" class="form-title">End Date</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                id="end_date" name="end_date" value="{{ old('end_date') }}">
                        </div>

                        <div class="gap-2 mt-3 d-flex">
                            <a href="{{ route('leave.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
@endsection
