<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overtime Requests</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>
<body>
    <x-layout>
        <div class="container mt-5">
            <h2>Overtime Requests</h2>
            <div class="card">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs m-t" id="overtimeRequestTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                            Pending <span class="badge bg-warning">{{ $overtimeRequests->where('Status', 'Pending')->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab">
                            Approved <span class="badge bg-success">{{ $overtimeRequests->where('Status', 'Approved')->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="Rejected-tab" data-bs-toggle="tab" data-bs-target="#Rejected" type="button" role="tab">
                            Rejected <span class="badge bg-danger">{{ $overtimeRequests->where('Status', 'Rejected')->count() }}</span>
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="overtimeRequestTabContent">
                    <!-- Pending Requests Tab -->
                    <div class="tab-pane fade show active" id="pending" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="pendingTable">
                                <colgroup>
                                    <col style="width:1% ;">
                                    <col style="width: 20% ;">
                                    <col style="width:20% ;">
                                    <col style="width:20% ;">
                                    <col style="width:20% ;">
                                    <col style="width:15% ;">
                                    </colgroup>
                                <thead>
                                    <tr class="table-primary">
                                        <th>ID</th>
                                        <th>Employee</th>
                                        <th>Date</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($overtimeRequests->where('Status', 'Pending') as $request)
                                        <tr>
                                            <td>{{ $request->id }}</td>
                                            <td>{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</td>
                                            <td>{{ \Carbon\Carbon::parse($request->Date)->format('M d, Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($request->StartTime)->format('h:i A') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($request->EndTime)->format('h:i A') }}</td>
                                            <td>
                                                <button onclick="updateOvertimeStatus({{ $request->id }}, 'Approved', '{{ $request->employee->FirstName }} {{ $request->employee->LastName }}')"
                                                        class="btn btn-success btn-sm">
                                                    Approve
                                                </button>
                                                <button onclick="updateOvertimeStatus({{ $request->id }}, 'Rejected', '{{ $request->employee->FirstName }} {{ $request->employee->LastName }}')"
                                                        class="btn btn-danger btn-sm">
                                                    Reject
                                                </button>
                                            </td>
                                                                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No pending requests</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Approved Requests Tab -->
                    <div class="tab-pane fade" id="approved" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="approvedTable">
                                <colgroup>
                                    <col style="width:1% ;">
                                    <col style="width: 20% ;">
                                    <col style="width:20% ;">
                                    <col style="width:20% ;">
                                    <col style="width:20% ;">
                                    <col style="width:15% ;">

                                    </colgroup>
                                <thead>
                                    <tr class="table-primary">
                                        <th>ID</th>
                                        <th>Employee</th>
                                        <th>Date</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Approved Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($overtimeRequests->where('Status', 'Approved') as $request)
                                        <tr>
                                            <td>{{ $request->id }}</td>
                                            <td>{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</td>
                                            <td>{{ \Carbon\Carbon::parse($request->Date)->format('M d, Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($request->StartTime)->format('h:i A') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($request->EndTime)->format('h:i A') }}</td>
                                            <td>{{ $request->updated_at->format('M d, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No approved requests</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Rejected Requests Tab -->
                    <div class="tab-pane fade" id="Rejected" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="RejectedTable">
                                <colgroup>
                                <col style="width:1% ;">
                                <col style="width: 20% ;">
                                <col style="width:20% ;">
                                <col style="width:20% ;">
                                <col style="width:20% ;">
                                <col style="width:15% ;">

                                </colgroup>
                                <thead>
                                    <tr class="table-primary">
                                        <th>ID</th>
                                        <th>Employee</th>
                                        <th>Date</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Rejected Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($overtimeRequests->where('Status', 'Rejected') as $request)
                                        <tr>
                                            <td>{{ $request->id }}</td>
                                            <td>{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</td>
                                            <td>{{ \Carbon\Carbon::parse($request->Date)->format('M d, Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($request->StartTime)->format('h:i A') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($request->EndTime)->format('h:i A') }}</td>
                                            <td>{{ $request->updated_at->format('M d, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No Rejected requests</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Initialize DataTables for each table -->
        <script>
            $(document).ready(function() {
                const table= ('#pendingTable, #approvedTable, #RejectedTable').each(function() {
                    $(this).DataTable({
                        scrollY: '300px', // Set vertical scroll height
                        scrollCollapse: true,
                        paging: true,
                        searching: true,
                        ordering: true,
                        info: true,
                        lengthChange: true,
                        pageLength: 10,
                        order:[[0,'desc']],
                        language: {
            search: "Search:",
            lengthMenu: "Display _MENU_ records per page",
            zeroRecords: "No matching records found",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "No entries available",
            infoFiltered: "(filtered from _MAX_ total entries)"
        },

                    });
                });
            });

            function updateOvertimeStatus(id, status, employeeName) {
    const action = status === 'Approved' ? 'approve' : 'reject';
    const icon = status === 'Approved' ? 'success' : 'warning';
    const buttonColor = status === 'Approved' ? '#198754' : '#dc3545';

    Swal.fire({
        title: `Confirm ${action}?`,
        html: `Do you want to ${action} the overtime request for<br><strong>${employeeName}</strong>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: buttonColor,
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Yes, ${action}!`,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Processing...',
                html: `${action.charAt(0).toUpperCase() + action.slice(1)}ing overtime request`,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Create form data
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('status', status);

            // Send request
            fetch(`/overtimerequests/${id}/status`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: `Overtime request has been ${action}ed!`,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Something went wrong');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error.message || 'Failed to process the request'
                });
            });
        }
    });
}

// Add custom styling for SweetAlert
const style = document.createElement('style');
style.textContent = `
    .swal2-popup {
        font-size: 1.2rem;
        border-radius: 15px;
    }
    .swal2-title {
        font-size: 1.8rem;
        color: #333;
    }
    .swal2-html-container {
        font-size: 1.1rem;
    }
    .swal2-confirm, .swal2-cancel {
        padding: 12px 24px !important;
        font-size: 1.1rem !important;
    }
    .swal2-icon {
        width: 5em !important;
        height: 5em !important;
    }
`;
document.head.appendChild(style);
        </script>
    </x-layout>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</body>
</html>
