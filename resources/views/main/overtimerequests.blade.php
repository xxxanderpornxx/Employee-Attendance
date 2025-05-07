<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overtime Requests</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
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
                                <thead>
                                    <tr>
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
                                                <form action="{{ route('overtimerequests.status.update', $request->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Approved">
                                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                                </form>
                                                <form action="{{ route('overtimerequests.status.update', $request->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Rejected">
                                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
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
                                <thead>
                                    <tr>
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
                $('#pendingTable, #approvedTable, #RejectedTable').each(function() {
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
