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
                
                {{-- Alert untuk notifikasi AJAX --}}
                <div id="alert-container"></div>

                <form id="leave-form">
                    @csrf
                    {{-- Employee --}}
                        <div class="form-group mb-3">
                            <label for="employee_id" class="form-title">Employee</label>
                            <select class="form-control" id="employee_id" name="employee_id">
                                <option value="">Select Employee</option>
                                {{-- Options akan diisi via AJAX --}}
                            </select>
                            <div class="invalid-feedback" id="employee_id-error"></div>
                        </div>
                        
                        {{-- start date --}}
                        <div class="form-group mb-3">
                            <label for="start_date" class="form-title">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
                            <div class="invalid-feedback" id="start_date-error"></div>
                        </div>
                        
                        {{-- end date --}}
                        <div class="form-group mb-3">
                            <label for="end_date" class="form-title">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}">
                            <div class="invalid-feedback" id="end_date-error"></div>
                        </div>

                        <div class="gap-2 mt-3 d-flex">
                            <a href="{{ route('leave.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>

                            <button type="submit" class="btn btn-primary" id="submit-btn">Submit</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
$(document).ready(function() {
    loadEmployees();
    
    function loadEmployees() {
        // Ambil data employees dari LeaveRequestController API
        $.ajax({
            url: '/api/leave-requests/employees',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Response dari API employees:', response);
                if (response.success) {
                    renderEmployees(response.data);
                } else {
                    showAlert('danger', response.message || 'Gagal mengambil data karyawan');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error loading employees:', xhr.responseText);
                showAlert('danger', 'Terjadi kesalahan saat mengambil data karyawan');
                
                // Fallback: render dummy data jika API employees tidak ada
                const dummyEmployees = [
                    {id: 1, name: 'Budi Santoso', nik: 'EMP001', department: 'Sewing'},
                    {id: 2, name: 'Fany Ghasani', nik: 'CTG001', department: 'Cutting'},
                    {id: 3, name: 'Hari Buadiawan', nik: 'FSG001', department: 'Finishing'},
                    {id: 4, name: 'Shinta Joana', nik: 'QA001', department: 'QA'},
                    {id: 6, name: 'Sri Andriani', nik: 'FSG0003', department: 'Finishing'}
                ];
                renderEmployees(dummyEmployees);
            }
        });
    }
    
    function renderEmployees(employees) {
        let html = '<option value="">Select Employee</option>';
        
        employees.forEach(function(employee) {
            html += `<option value="${employee.id}">
                    ${employee.name} - ${employee.nik} (${employee.department})
                    </option>`;
        });
        
        $('#employee_id').html(html);
    }
    
    $('#leave-form').on('submit', function(e) {
        e.preventDefault();
        
        // Reset form errors
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').html('');
        
        const formData = {
            employee_id: $('#employee_id').val(),
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            _token: $('input[name="_token"]').val()
        };
        
        // Disable submit button
        $('#submit-btn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
        
        $.ajax({
            url: '/api/leave-requests',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    // Redirect to index after 2 seconds
                    setTimeout(function() {
                        window.location.href = '{{ route("leave.index") }}';
                    }, 2000);
                } else {
                    showAlert('danger', response.message || 'Gagal menyimpan data');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON || {};
                
                if (xhr.status === 422 && response.errors) {
                    // Show validation errors
                    Object.keys(response.errors).forEach(function(key) {
                        $(`#${key}`).addClass('is-invalid');
                        $(`#${key}-error`).html(response.errors[key][0] || '');
                    });
                    showAlert('danger', response.message || 'Terjadi kesalahan validasi');
                } else {
                    showAlert('danger', response.message || 'Terjadi kesalahan saat menyimpan data');
                }
            },
            complete: function() {
                // Re-enable submit button
                $('#submit-btn').prop('disabled', false).html('Submit');
            }
        });
    });
    
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        $('#alert-container').html(alertHtml);
        
        // Auto hide after 5 seconds
        setTimeout(function() {
            $('#alert-container .alert').alert('close');
        }, 5000);
    }
});
</script>
@endsection
