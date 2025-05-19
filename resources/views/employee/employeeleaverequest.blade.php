<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Leave & Overtime Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <x-employeelayout>
        <div class="container mt-5">
            {{-- Show validation errors and success messages --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Request History with Forms --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Request History</h3>
                    <div>
                        <button type="button" class="btn me-2 text-white" style="background-color: #009688;" data-bs-toggle="modal" data-bs-target="#leaveModal">
                            <i class="bi bi-plus-circle"></i> New Leave Request
                        </button>
                        <button type="button" class="btn text-white" style="background-color: #009688;" data-bs-toggle="modal" data-bs-target="#overtimeModal">
                            <i class="bi bi-plus-circle"></i> New Overtime Request
                        </button>
                    </div>
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

        {{-- Leave Request Modal --}}
        <div class="modal fade" id="leaveModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title">New Leave Request</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
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
                            <div class="text-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-info text-white">Submit Request</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Overtime Request Modal --}}
        <div class="modal fade" id="overtimeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title">New Overtime Request</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('employee.overtime.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="Date" min="{{ date('Y-m-d') }}" required>
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
                            <div class="text-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-info text-white">Submit Request</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add date validation script --}}
     <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show success messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 1500,
                showConfirmButton: false
            });
        @endif

        // Show validation errors
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: '<ul class="list-unstyled">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>'
            });
        @endif

        // Form submissions
        const leaveForm = document.querySelector('#leaveModal form');
        const overtimeForm = document.querySelector('#overtimeModal form');

        leaveForm.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Submitting Leave Request...',
                text: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    this.submit();
                }
            });
        });

        overtimeForm.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Submitting Overtime Request...',
                text: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    this.submit();
                }
            });
        });

        // Replace alert with SweetAlert for date/time validation
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const startTime = document.getElementById('start_time');
        const endTime = document.getElementById('end_time');

        startDate.addEventListener('change', function() {
            endDate.min = this.value;
        });

        endTime.addEventListener('change', function() {
            if(startTime.value && this.value <= startTime.value) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Time',
                    text: 'End time must be after start time',
                    confirmButtonColor: '#3085d6'
                });
                this.value = '';
            }
        });
    });

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
    </x-employeelayout>
</body>
</html>
