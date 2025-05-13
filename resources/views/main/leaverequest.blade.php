<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request </title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
    <x-layout>
        <div class="container mt-5">
            <h2>Leave Requests</h2>
            <div class="card ">
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs m-t" id="leaveRequestTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                        Pending <span class="badge bg-warning">{{ $leaveRequests->where('Status', 'Pending')->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab">
                        Approved <span class="badge bg-success">{{ $leaveRequests->where('Status', 'Approved')->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="denied-tab" data-bs-toggle="tab" data-bs-target="#denied" type="button" role="tab">
                        Rejected <span class="badge bg-danger">{{ $leaveRequests->where('Status', 'Denied')->count() }}</span>
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="leaveRequestTabContent">
                <!-- Pending Requests Tab -->
                <div class="tab-pane fade show active" id="pending" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="pendingTable">
                            <colgroup>
                                <col style="width:1% ;">
                                <col style="width: 15% ;">
                                <col style="width:10% ;">
                                <col style="width:10% ;">
                                <col style="width:10% ;">
                                <col style="width:25% ;">
                                <col style="width: 12% ;">
                                </colgroup>
                            <thead>
                                <tr class="table-primary">
                                    <th>ID</th>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Reason</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leaveRequests->where('Status', 'Pending') as $request)
                                    <tr>
                                        <td>{{ $request->id }}</td>
                                        <td>{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</td>
                                        <td>{{ $request->LeaveType }}</td>
                                        <td>{{ \Carbon\Carbon::parse($request->StartDate)->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($request->EndDate)->format('M d, Y') }}</td>
                                        <td>{{ $request->Reason }}</td>
                                        <td>
                                            <button onclick="updateStatus({{ $request->id }}, 'Approved', '{{ $request->employee->FirstName }} {{ $request->employee->LastName }}')"
                                                    class="btn btn-success btn-sm">
                                                Approve
                                            </button>
                                            <button onclick="updateStatus({{ $request->id }}, 'Denied', '{{ $request->employee->FirstName }} {{ $request->employee->LastName }}')"
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
                                <col style="width: 15% ;">
                                <col style="width:10% ;">
                                <col style="width:10% ;">
                                <col style="width:10% ;">
                                <col style="width:25% ;">
                                <col style="width: 12% ;">
                                </colgroup>
                            <thead>
                                <tr class="table-primary">
                                    <th>ID</th>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Reason</th>
                                    <th>Rejected Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leaveRequests->where('Status', 'Approved') as $request)
                                    <tr>
                                        <td>{{ $request->id }}</td>
                                        <td>{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</td>
                                        <td>{{ $request->LeaveType }}</td>
                                        <td>{{ \Carbon\Carbon::parse($request->StartDate)->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($request->EndDate)->format('M d, Y') }}</td>
                                        <td>{{ $request->Reason }}</td>
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

                <!-- Denied Requests Tab -->
                <div class="tab-pane fade" id="denied" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="deniedTable">
                            <colgroup>
                                <col style="width:1% ;">
                                <col style="width: 15% ;">
                                <col style="width:10% ;">
                                <col style="width:10% ;">
                                <col style="width:10% ;">
                                <col style="width:25% ;">
                                <col style="width: 12% ;">
                                </colgroup>
                            <thead>
                                <tr class="table-primary">
                                    <th>ID</th>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Reason</th>
                                    <th>Rejected Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leaveRequests->where('Status', 'Denied') as $request)
                                    <tr>
                                        <td>{{ $request->id }}</td>
                                        <td>{{ $request->employee->FirstName }} {{ $request->employee->LastName }}</td>
                                        <td>{{ $request->LeaveType }}</td>
                                        <td>{{ \Carbon\Carbon::parse($request->StartDate)->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($request->EndDate)->format('M d, Y') }}</td>
                                        <td>{{ $request->Reason }}</td>
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
                $('#pendingTable, #approvedTable, #deniedTable').each(function() {
                    $(this).DataTable({
                        order: [[0, 'desc']], // Sort by ID descending
                        pageLength: 10,
                        responsive: true
                    });
                });
            });
        </script>
        <script>
function updateStatus(id, status, employeeName) {
    const action = status === 'Approved' ? 'approve' : 'reject';
    const icon = status === 'Approved' ? 'success' : 'warning';
    const buttonColor = status === 'Approved' ? '#198754' : '#dc3545';

    Swal.fire({
        title: `Are you sure?`,
        text: `Do you want to ${action} ${employeeName}'s leave request?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: buttonColor,
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Yes, ${action} it!`,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Processing...',
                html: `${action.charAt(0).toUpperCase() + action.slice(1)}ing leave request`,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send the request
            fetch(`/leaverequests/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: icon,
                        title: 'Success!',
                        text: `Leave request ${action}ed successfully!`,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Reload the page to update the tables
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.message || 'Something went wrong!'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An error occurred while processing the request.'
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
    .swal2-confirm {
        padding: 12px 24px !important;
        font-size: 1.1rem !important;
    }
    .swal2-cancel {
        padding: 12px 24px !important;
        font-size: 1.1rem !important;
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
