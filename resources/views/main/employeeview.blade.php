<x-layout>
    <head>
        <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <!-- Employee Details -->
            <div class="row mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Personal Information</h4>
                    <!-- View Leave Credit Points Button -->
                    <a class="btn btn-secondary" href="/">
                        <i class="bi bi-clipboard-check"></i> View Leave Credits
                    </a>
                </div>
            </div>

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
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control" value="{{ $employee->Email }}" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="text" class="form-control" value="{{ $employee->ContactNumber }}" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Hire Date</label>
                    <input type="text" class="form-control" value="{{ $employee->HireDate }}" readonly>
                </div>
            </div>

            <div class="row mb-4">
                <h4>Job Information</h4>
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

            <div class="row">
                <div class="col text-center">
                    <label class="form-label">QR Code</label>
                    <div @class(['p-4', 'font-bold' => true])>
                        @if ($employee->QRcode)
                            <img id="qrCodeImage" src="{{ asset('storage/qrcodes/employee_' . $employee->id . '.png') }}" alt="QR Code" style="width: 150px; height: 150px;">
                        @else
                            <p>No QR Code available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </body>
</x-layout>
