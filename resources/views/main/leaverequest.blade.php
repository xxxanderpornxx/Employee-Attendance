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
                        Denied <span class="badge bg-danger">{{ $leaveRequests->where('Status', 'Denied')->count() }}</span>
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="leaveRequestTabContent">
                <!-- Pending Requests Tab -->
                <div class="tab-pane fade show active" id="pending" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="pendingTable">
                            <thead>
                                <tr>
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
                                            <form action="{{ route('leaverequests.status.update', $request->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="Approved">
                                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                            </form>
                                            <form action="{{ route('leaverequests.status.update', $request->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="Denied">
                                                <button type="submit" class="btn btn-danger btn-sm">Deny</button>
                                            </form>

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
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Reason</th>
                                    <th>Approved Date</th>
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
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Reason</th>
                                    <th>Denied Date</th>
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
                                        <td colspan="6" class="text-center">No denied requests</td>
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
    </x-layout>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</body>
</html>
