<x-layout>
    <head>
        <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <div class="container mt-5">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="javascript:history.back()" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#leaveCreditsModal">
                    <i class="bi bi-clipboard-check"></i> Leave Credits
                </button>
            </div>

            <!-- Personal Information Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Personal Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" value="{{ $employee->FirstName }}" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control" value="{{ $employee->MiddleName }}" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" value="{{ $employee->LastName }}" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sex</label>
                            <input type="text" class="form-control" value="{{ $employee->Sex }}" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="text" class="form-control" value="{{ $employee->DateOfBirth }}" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" value="{{ $employee->Address }}" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" value="{{ $employee->ContactNumber }}" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" value="{{ $employee->Email }}" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Hire Date</label>
                            <input type="text" class="form-control" value="{{ $employee->HireDate }}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Information Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Job Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Department</label>
                            <input type="text" class="form-control" value="{{ $employee->department ? $employee->department->DepartmentName : 'N/A' }}" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Position</label>
                            <input type="text" class="form-control" value="{{ $employee->position ? $employee->position->PositionName : 'N/A' }}" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Base Salary</label>
                            <input type="text" class="form-control" value="{{ $employee->BaseSalary }}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>QR Code</h4>
                </div>
                <div class="card-body text-center">
                    @if ($employee->QRcode)
                        <img id="qrCodeImage" src="{{ asset('storage/qrcodes/employee_' . $employee->id . '.png') }}" alt="QR Code" style="width: 150px; height: 150px;">
                    @else
                        <p>No QR Code available</p>
                    @endif
                </div>
            </div>

            <!-- Leave Credits Card -->

        </div>

        <!-- Leave Credits Modal -->
        <div class="modal fade" id="leaveCreditsModal" tabindex="-1" aria-labelledby="leaveCreditsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="leaveCreditsModalLabel">Leave Credits</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Leave Type</th>
                                    <th>Total Credits</th>
                                    <th>Used Credits</th>
                                    <th>Remaining Credits</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Sick Leave</td>
                                    <td>{{ $employee->leaveBalance->SickLeave ?? 0 }}</td>
                                    <td>0</td>
                                    <td>{{ $employee->leaveBalance->SickLeave ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Vacation Leave</td>
                                    <td>{{ $employee->leaveBalance->VacationLeave ?? 0 }}</td>
                                    <td>0</td>
                                    <td>{{ $employee->leaveBalance->VacationLeave ?? 0 }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</x-layout>
