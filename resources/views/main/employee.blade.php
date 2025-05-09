<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/position.css') }}">

    <link rel="stylesheet" href="{{ asset('css/employee.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
  <body>
    <x-layout>

        <div class="col-12">
            <div class="row p-3 align-items-center">
                <div class="col">
                    <h1 class="pb-3">Employees</h1>
                </div>
                <div class="col-auto ms-auto">
                    <button class="addemployeebutton" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                        <i class="bi bi-plus"></i> Add Employee
                    </button>
                </div>
            </div>
            <!-- Container for Search, Sort, and Data Table -->
            <div class="card" >
                <div class="card-body" style="height: 450px;">
                    <!-- Data Table -->
                    <div class="" style="height: 450px">
                        <table id="employeeTable" class="table table-striped table-bordered">
                            <colgroup>
                                <col style="width:3%;">
                                <col style="width: 12%;">
                                <col style="width: 14%;">
                                <col style="width: 10%;">
                                <col style="width: 5%;">
                                <col style="width: 5%;">
                                <col style="width: 10%;">
                                <col style="width: 15%;">
                            </colgroup>
                            <thead>
                                <tr class="table-primary">
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Department|Position</th>
                                    <th>Shift</th>
                                    <th>Sex</th>
                                    <th>Age</th>
                                    <th>Address</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td>{{ $employee->id }}</td>
                                        <td>{{ $employee->FirstName . ' ' . ($employee->MiddleName ? substr($employee->MiddleName, 0, 1) . '.' : '') . ' ' . $employee->LastName }}</td>
                                        <td>{{ $employee->position && $employee->department ? $employee->department->DepartmentName : 'N/A' }} | {{ $employee->position ? $employee->position->PositionName : 'N/A' }}</td>
                                        <td>
                                            @if ($employee->shifts && $employee->shifts->count() > 0)
                                                @foreach ($employee->shifts as $shift)
                                                    <div>{{ \Carbon\Carbon::createFromFormat('H:i:s', $shift->StartTime)->format('h:i A') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $shift->EndTime)->format('h:i A') }}</div>
                                                @endforeach
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $employee->Sex ?? 'N/A' }}</td>
                                        <td>{{ $employee->DateOfBirth ? (int) \Carbon\Carbon::createFromFormat('Y-m-d', $employee->DateOfBirth)->diffInYears(now()) : 'N/A' }}</td>
                                        <td>{{ $employee->Address }}</td>
                                        <td class="text-center">
                                            <!-- Edit Button -->
                                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editEmployeeModal-{{ $employee->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <!-- Delete Button -->
                                            <form method="POST" action="{{ route('Employees.destroy', $employee->id) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this employee?');">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>

                                            <!-- View Button -->
                                            <a href="{{ route('employeeview', ['id' => $employee->id]) }}" class="btn btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <!-- Assign Shift Button -->
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignShiftModal-{{ $employee->id }}">
                                                <i class="bi bi-calendar-check"></i>
                                            </button>



                                            <!-- Assign Shift Modal -->
                                            <div class="modal fade" id="assignShiftModal-{{ $employee->id }}" tabindex="-1" aria-labelledby="assignShiftModalLabel-{{ $employee->id }}" aria-hidden="true" role="dialog">
                                                <div class="modal-dialog modal-lg"> <!-- Changed modal size to modal-lg for wider modal -->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="assignShiftModalLabel-{{ $employee->id }}">Assign Schedules to {{ $employee->FirstName }} {{ $employee->LastName }}</h4>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST" action="{{ route('assignSchedule') }}">
                                                                @csrf
                                                                <input type="hidden" name="EmployeeID" value="{{ $employee->id }}">

                                                                <!-- Display Assigned Schedules -->
                                                                <div class="mb-3">
                                                                    <h5>Currently Assigned Schedules:</h5>
                                                                    @if ($employee->schedules && $employee->schedules->count() > 0)
                                                                        <div class="table-responsive " style=" height: 200px; overflow-y: auto;">
                                                                            <table class="table table-bordered">
                                                                                <thead class="table-light">
                                                                                    <tr>
                                                                                        <th>Day</th>
                                                                                        <th>Shift Start</th>
                                                                                        <th>Shift End</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($employee->schedules as $schedule)
                                                                                        <tr>
                                                                                            <td>{{ $schedule->DayOfWeek }}</td>
                                                                                            <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->shift->StartTime)->format('h:i A') }}</td>
                                                                                            <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->shift->EndTime)->format('h:i A') }}</td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    @else
                                                                        <p>No schedules assigned.</p>
                                                                    @endif
                                                                </div>
                                                                <hr>

                                                                <hr>
                                                                <!-- Shift 1 Selection -->
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label for="ShiftID1-{{ $employee->id }}" class="form-label">Select First Shift</label>
                                                                            <select class="form-select" id="ShiftID1-{{ $employee->id }}" name="ShiftIDs[1]" required>
                                                                                <option value="" disabled selected>Select a shift</option>
                                                                                @foreach ($shifts as $shift)
                                                                                    <option value="{{ $shift->id }}">
                                                                                        {{ $shift->StartTime }} - {{ $shift->EndTime }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Select Days for First Shift</label>
                                                                        <div class="row">
                                                                            @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                                                <div class="col-md-6">
                                                                                    <div class="form-check">
                                                                                        <input class="form-check-input" type="checkbox" name="Days[1][]" value="{{ $day }}" id="Shift1-{{ $day }}-{{ $employee->id }}">
                                                                                        <label class="form-check-label" for="Shift1-{{ $day }}-{{ $employee->id }}">
                                                                                            {{ $day }}
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <hr>

                                                                <hr>
                                                                  <!-- Shift 2 Selection -->
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="mb-3">
                                                                                 <label for="ShiftID2-{{ $employee->id }}" class="form-label">Select Second Shift (Optional)</label>
                                                                                 <select class="form-select" id="ShiftID2-{{ $employee->id }}" name="ShiftIDs[2]">
                                                                                        <option value="" disabled selected>Select a shift</option>
                                                                                        @foreach ($shifts as $shift)
                                                                                            <option value="{{ $shift->id }}">
                                                                                            {{ $shift->StartTime }} - {{ $shift->EndTime }}</option>
                                                                                        @endforeach
                                                                                 </select>
                                                                             </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                              <label class="form-label">Select Days for Second Shift</label>
                                                                               <div class="row">
                                                                                    @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                                                      <div class="col-md-6">
                                                                                           <div class="form-check">
                                                                                             <input class="form-check-input" type="checkbox" name="Days[2][]" value="{{ $day }}" id="Shift2-{{ $day }}-{{ $employee->id }}">
                                                                                              <label class="form-check-label" for="Shift2-{{ $day }}-{{ $employee->id }}">
                                                                                                 {{ $day }}
                                                                                                </label>
                                                                                         </div>
                                                                                    </div>
                                                                                 @endforeach
                                                                             </div>
                                                                            </div>
                                                                        </div>

                                                                <hr>

                                                                <hr>
                                                                <button type="submit" class="btn btn-success">Assign Schedules</button>
                                                            </form>
                                                            <!-- Remove Schedules Button -->
                                                            <form method="POST" action="{{ route('removeAll', $employee->id) }}" style="margin-top: 10px;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to remove all schedules and shifts for this employee?');">
                                                                    Remove All Schedules and Shifts
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal for Editing an Employee -->
                                    <div class="modal fade" id="editEmployeeModal-{{ $employee->id }}" tabindex="-1" aria-labelledby="editEmployeeModalLabel-{{ $employee->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="editEmployeeModalLabel-{{ $employee->id }}">Edit Employee</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="{{ route('Employees.update', $employee->id) }}">
                                                        @csrf
                                                        @method('PUT')

                                                        <!-- Name Row -->
                                                        <div class="row">
                                                            <h4>Personal Information</h4>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="FirstName-{{ $employee->id }}" class="form-label">First Name</label>
                                                                <input type="text" class="form-control" id="FirstName-{{ $employee->id }}" name="FirstName" value="{{ $employee->FirstName }}" required>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="MiddleName-{{ $employee->id }}" class="form-label">Middle Name</label>
                                                                <input type="text" class="form-control" id="MiddleName-{{ $employee->id }}" name="MiddleName" value="{{ $employee->MiddleName }}">
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="LastName-{{ $employee->id }}" class="form-label">Last Name</label>
                                                                <input type="text" class="form-control" id="LastName-{{ $employee->id }}" name="LastName" value="{{ $employee->LastName }}" required>
                                                            </div>
                                                        </div>

                                                        <!-- Sex, Date of Birth, and Address Row -->
                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <label for="Sex-{{ $employee->id }}" class="form-label">Sex</label>
                                                                <select class="form-select" id="Sex-{{ $employee->id }}" name="Sex" required>
                                                                    <option value="Male" {{ $employee->Sex == 'Male' ? 'selected' : '' }}>Male</option>
                                                                    <option value="Female" {{ $employee->Sex == 'Female' ? 'selected' : '' }}>Female</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="DateOfBirth-{{ $employee->id }}" class="form-label">Date of Birth</label>
                                                                <input type="date" class="form-control" id="DateOfBirth-{{ $employee->id }}" name="DateOfBirth" value="{{ $employee->DateOfBirth }}" required>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="Address-{{ $employee->id }}" class="form-label">Address</label>
                                                                <input type="text" class="form-control" id="Address-{{ $employee->id }}" name="Address" value="{{ $employee->Address }}" required>
                                                            </div>
                                                        </div>

                                                        <!-- Email, Contact Number, and Hire Date Row -->
                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <label for="Email-{{ $employee->id }}" class="form-label">Email</label>
                                                                <input type="email" class="form-control" id="Email-{{ $employee->id }}" name="Email" value="{{ $employee->Email }}" required>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="ContactNumber-{{ $employee->id }}" class="form-label">Contact Number</label>
                                                                <input type="text" class="form-control" id="ContactNumber-{{ $employee->id }}" name="ContactNumber" value="{{ $employee->ContactNumber }}" required>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="HireDate-{{ $employee->id }}" class="form-label">Hire Date</label>
                                                                <input type="date" class="form-control" id="HireDate-{{ $employee->id }}" name="HireDate" value="{{ $employee->HireDate }}" required>
                                                            </div>
                                                        </div>

                                                        <!-- Position, Department, and Base Salary Row -->
                                                        <div class="row">
                                                            <h4>Job Information
                                                            </h4>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="DepartmentID-{{ $employee->id }}" class="form-label">Department</label>
                                                                <select class="form-select" id="DepartmentID-{{ $employee->id }}" name="DepartmentID" required>
                                                                    @foreach ($departments as $department)
                                                                        <option value="{{ $department->id }}" {{ $employee->DepartmentID == $department->id ? 'selected' : '' }}>
                                                                            {{ $department->DepartmentName }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="PositionID-{{ $employee->id }}" class="form-label">Position</label>
                                                                <select class="form-select" id="PositionID-{{ $employee->id }}" name="PositionID" required>
                                                                    @foreach ($positions as $position)
                                                                        <option value="{{ $position->id }}" {{ $employee->PositionID == $position->id ? 'selected' : '' }}>
                                                                            {{ $position->PositionName }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="BaseSalary-{{ $employee->id }}" class="form-label">Base Salary</label>
                                                                <input type="number" class="form-control" id="BaseSalary-{{ $employee->id }}" name="BaseSalary" value="{{ $employee->BaseSalary }}" required>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal for Viewing an Employee -->
                                    <div class="modal fade" id="viewEmployeeModal-{{ $employee->id }}" tabindex="-1" aria-labelledby="viewEmployeeModalLabel-{{ $employee->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="viewEmployeeModalLabel-{{ $employee->id }}">View Employee</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Employee Details -->
                                                    <div class="row">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h4>Personal Information</h4>
                                                            <!-- View Leave Credit Points Button -->
                                                            <a class="btn btn-secondary" href="/leavecreditpoint">
                                                                <i class="bi bi-clipboard-check"></i> View Leave Credits
                                                            </a>
                                                            </button>
                                                        </div>

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

                                                    <div class="row">
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

                                                    <div class="mb-3" style="text-align: center;">
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
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Adding a New Employee -->
        <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('Employees.store') }}">
                            @csrf
                            <!-- Name Row -->
                            <div class="row">
                                <h4>Personal Information</h4>
                                <div class="col-md-4 mb-3">
                                    <label for="FirstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="FirstName" name="FirstName" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="MiddleName" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" id="MiddleName" name="MiddleName">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="LastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="LastName" name="LastName" required>
                                </div>
                            </div>

                            <!-- Sex, Date of Birth, and Address Row -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="Sex" class="form-label">Sex</label>
                                    <select class="form-select" id="Sex" name="Sex" required>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="DateOfBirth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="DateOfBirth" name="DateOfBirth" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="Address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="Address" name="Address" required>
                                </div>
                            </div>

                            <!-- Email, Contact Number, and Hire Date Row -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="Email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="Email" name="Email" required placeholder="Example@example.com">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ContactNumber" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control" id="ContactNumber" name="ContactNumber" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="HireDate" class="form-label">Hire Date</label>
                                    <input type="date" class="form-control" id="HireDate" name="HireDate" required>
                                </div>
                            </div>

                            <!-- Position, Department, and Base Salary Row -->
                            <div class="row">
                                <h4>Job Information</h4>
                                <div class="col-md-4 mb-3">
                                    <label for="DepartmentID" class="form-label">Department</label>
                                    <select class="form-select" id="DepartmentID" name="DepartmentID" required>
                                        <option value="" disabled selected>Select a department</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->DepartmentName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PositionID" class="form-label">Position</label>
                                    <select class="form-select" id="PositionID" name="PositionID" required>
                                        <option value="" disabled selected>Select a position</option>
                                        @foreach ($positions as $position)
                                            <option value="{{ $position->id }}">{{ $position->PositionName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="BaseSalary" class="form-label">Base Salary</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="BaseSalary" name="BaseSalary" required>
                                    </div>
                                </div>
                            </div>

                            <!-- QR Code Section -->
                            <div class="mb-3">
                                <label for="QRcode" class="form-label">QR Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="QRcode" name="QRcode" readonly>
                                    <button type="button" class="btn btn-secondary" onclick="generateQRCode()">Generate QR</button>
                                </div>
                                <img id="qrCodeImage" src="" alt="QR Code" style="width: 150px; height: 150px; display: none;">
                            </div>

                            <script>
                                function generateQRCode() {
                                    const employeeId = $('#FirstName').val() + ' ' + $('#LastName').val(); // Example: Use employee name
                                    $.ajax({
                                        url: `/employees/generate-qr`, // Correct endpoint
                                        method: 'POST',
                                        data: {
                                            _token: '{{ csrf_token() }}', // CSRF token for security
                                            employeeId: employeeId // Pass employee data
                                        },
                                        success: function (response) {
                                            $('#QRcode').val(response.qrCodePath); // Update the QR code path
                                            $('#qrCodeImage').attr('src', response.qrCodePath).show(); // Show the QR code image
                                            alert('QR Code generated successfully!');
                                        },
                                        error: function (xhr) {
                                            console.error(xhr.responseText); // Log the error for debugging
                                            alert('Failed to generate QR Code.');
                                        }
                                    });
                                }
                            </script>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
        <script>
     $(document).ready(function () {
                $('#employeeTable').DataTable({
                    scrollY: '300px', // Set vertical scroll height
                    scrollCollapse: true,
                    paging: true,
                    searching: true,
                    ordering: true,
                    info: true,
                    lengthChange: true,
                    pageLength: 10,
                    language: {
                        search: "Search:",
                        lengthMenu: "Display _MENU_ records per page",
                        zeroRecords: "No matching records found",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "No entries available",
                        infoFiltered: "(filtered from _MAX_ total entries)"
                    }
                });
            });
        </script>
    </x-layout>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
  </body>
</html>
