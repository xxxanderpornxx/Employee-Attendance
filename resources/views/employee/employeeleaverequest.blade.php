<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Leave & Overtime Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <x-employeelayout>
        <div class="container mt-5">
            {{-- Show any validation errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Show success message --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                {{-- Leave Request Form --}}
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-secondary text-white">
                            <h3 class="mb-0">Leave Request Form</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('employee.leave.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="leave_type" class="form-label">Leave Type</label>
                                    <select class="form-select" id="leave_type" name="LeaveType" required>
                                        <option value="">Select Leave Type</option>
                                        <option value="Sick Leave">Sick Leave</option>
                                        <option value="Vacation Leave">Vacation Leave</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="StartDate" required>
                                </div>
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="EndDate" required>
                                </div>
                                <div class="mb-3">
                                    <label for="reason" class="form-label">Reason</label>
                                    <textarea class="form-control" id="reason" name="Reason" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-info w-100">Submit Leave Request</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Overtime Request Form --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h3 class="mb-0">Overtime Request Form</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('employee.overtime.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="date" name="Date"
                                        min="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="start_time" class="form-label">Start Time</label>
                                        <input type="time" class="form-control" id="start_time" name="StartTime" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="end_time" class="form-label">End Time</label>
                                        <input type="time" class="form-control" id="end_time" name="EndTime" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-info w-100">Submit Overtime Request</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Request History --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h3 class="mb-0">Request History</h3>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="requestTabs" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active" id="leave-tab" data-bs-toggle="tab" data-bs-target="#leave" type="button" role="tab">
                                        Leave Requests
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" id="overtime-tab" data-bs-toggle="tab" data-bs-target="#overtime" type="button" role="tab">
                                        Overtime Requests
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content mt-3">
                                <div class="tab-pane fade show active" id="leave" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Leave Type</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Reason</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($leaveRequests ?? [] as $request)
                                                    <tr>
                                                        <td>{{ $request->id }}</td>
                                                        <td>{{ $request->LeaveType }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($request->StartDate)->format('M d, Y') }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($request->EndDate)->format('M d, Y') }}</td>
                                                        <td>{{ $request->Reason }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $request->Status == 'Approved' ? 'success' :
                                                                ($request->Status == 'Denied' ? 'danger' : 'warning') }}">
                                                                {{ $request->Status }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center">No leave requests found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="overtime" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Date</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($overtimeRequests ?? [] as $request)
                                                    <tr>
                                                        <td>{{ $request->id }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($request->Date)->format('M d, Y') }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($request->StartTime)->format('h:i A') }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($request->EndTime)->format('h:i A') }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $request->Status == 'Approved' ? 'success' :
                                                                ($request->Status == 'Rejected' ? 'danger' : 'warning') }}">
                                                                {{ $request->Status }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">No overtime requests found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add date validation script --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Validate leave request dates
                const startDate = document.getElementById('start_date');
                const endDate = document.getElementById('end_date');

                startDate.addEventListener('change', function() {
                    endDate.min = this.value;
                });

                // Validate overtime times
                const startTime = document.getElementById('start_time');
                const endTime = document.getElementById('end_time');

                endTime.addEventListener('change', function() {
                    if(startTime.value && this.value <= startTime.value) {
                        alert('End time must be after start time');
                        this.value = '';
                    }
                });
            });
        </script>
    </x-employeelayout>
</body>
</html>
