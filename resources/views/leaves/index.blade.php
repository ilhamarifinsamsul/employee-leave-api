@extends('layouts.app')
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

{{-- Alert untuk notifikasi AJAX --}}
<div id="alert-container"></div>

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
                        <th>Total Hari Cuti</th>
                        <th>Sisa Hari</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="leave-requests-tbody">
                    {{-- Data akan diisi via AJAX --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
$(document).ready(function() {
    loadLeaveRequests();

    function loadLeaveRequests() {
        $.ajax({
            url: '/api/leave-requests',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    renderLeaveRequests(response.data);
                } else {
                    showAlert('danger', response.message || 'Gagal mengambil data');
                }
            },
            error: function(xhr) {
                showAlert('danger', 'Terjadi kesalahan saat mengambil data');
            }
        });
    }

    function formatDate(date){
        return new Date(date).toLocaleDateString('id-ID');
    }

    function renderLeaveRequests(data) {
        let html = '';
        
        if (data.length === 0) {
            html = '<tr><td colspan="9" class="text-center">Tidak ada data pengajuan cuti</td></tr>';
        } else {
            data.forEach(function(leave, index) {
                const employee = leave.employee || {};
                const statusBadge = getStatusBadge(leave.status);
                const actions = getActions(leave);
                
                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${employee.nik || '-'}</td>
                        <td>${employee.name || '-'}</td>
                        <td>${employee.department || '-'}</td>
                        <td>
                            ${formatDate(leave.start_date)}
                            <span class="text-muted">s/d</span>
                            ${formatDate(leave.end_date)}
                        </td>
                        <td>
                            <span class="badge bg-info">
                                ${leave.total_days} hari
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                ${employee?.leave_balance ?? 0} hari
                            </span>
                        </td>
                        <td>${statusBadge}</td>
                        <td>${actions}</td>
                    </tr>
                `;
            });
        }
        
        $('#leave-requests-tbody').html(html);
    }

    function getStatusBadge(status) {
        const badges = {
            'pending': '<span class="badge bg-warning">pending</span>',
            'approved': '<span class="badge bg-success">approved</span>',
            'rejected': '<span class="badge bg-danger">rejected</span>'
        };
        return badges[status] || '<span class="badge bg-secondary">' + status + '</span>';
    }

    function getActions(leave) {
        if (leave.status !== 'pending') {
            return '<span class="text-muted fst-italic">Tidak Ada Aksi</span>';
        }
        
        return `
            <div class="d-flex gap-2">
                <button class="btn btn-success btn-sm" onclick="approveLeave(${leave.id})">
                    Approve
                </button>
                <button class="btn btn-danger btn-sm" onclick="rejectLeave(${leave.id})">
                    Reject
                </button>
            </div>
        `;
    }

    window.approveLeave = function(id) {

    if (!confirm('Approve cuti ini?')) return;

    const btn = event.target;
    $(btn).prop('disabled', true).text('Loading...');

    $.ajax({
        url: `/api/leave-requests/${id}/approve`,
        method: 'PATCH',
        success: function(response) {
            showAlert('success', response.message);
            loadLeaveRequests();
        },
        error: function(xhr) {
            const message = xhr.responseJSON?.message || 'Approve gagal';
            showAlert('danger', message);
        },
        complete: function(){
            $(btn).prop('disabled', false).text('Approve');
        }
    });
}

    window.rejectLeave = function(id) {
        if (!confirm('Reject cuti ini?')) return;
        
        $.ajax({
            url: `/api/leave-requests/${id}/reject`,
            method: 'PATCH',
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    loadLeaveRequests();
                } else {
                    showAlert('danger', response.message || 'Reject gagal');
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Reject gagal';
                showAlert('danger', message);
            }
        });
    };

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



