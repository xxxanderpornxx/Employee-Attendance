<x-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/employee.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <div class="col-12">
        <div class="row p-3 pb-5">
            <div class="col-auto">
                <h1 class="pb-3">Employees</h1>
                <button class="addemployeebutton" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    <i class="bi bi-plus"></i> Add Employee
                </button>
            </div>
        </div>

        <!-- Container for Search, Sort, and Data Table -->
        <div class="card">
            <div class="card-body">
                <!-- Search and Sort Bar -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <!-- Search Bar -->
                        <input type="text" class="form-control" id="searchBar" placeholder="Search employees...">
                    </div>
                    <div class="col-md-6 text-end">
                        <!-- Sort Dropdown -->
                        <select class="form-select w-auto d-inline-block" id="sortBar">
                            <option value="id_asc">Sort by ID (Ascending)</option>
                            <option value="id_desc">Sort by ID (Descending)</option>
                            <option value="name_asc">Sort by Name (A-Z)</option>
                            <option value="name_desc">Sort by Name (Z-A)</option>
                        </select>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-striped table-bordered">
                        <colgroup>
                            <col style="width:3%;">
                            <col style="width: 20%;">
                            <col style="width: 30%;">
                            <col style="width: 0%;">
                            <col style="width: 5%;">
                            <col style="width: 5%;">
                            <col style="width: 15%;">
                            <col style="width: 20%;">
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
                                    <td>{{ $employee->FirstName . ' ' . $employee->MiddleName . ' ' . $employee->LastName }}</td>
                                    <td>{{ $employee->position && $employee->department ? $employee->department->DepartmentName : 'N/A' }} | {{ $employee->position ? $employee->position->PositionName : 'N/A' }}</td>
                                    <td>N/A</td>
                                    <td>{{ $employee->Sex ?? 'N/A' }}</td>
                                    <td>{{ $employee->DateOfBirth ? (int) \Carbon\Carbon::createFromFormat('Y-m-d', $employee->DateOfBirth)->diffInYears(now()) : 'N/A' }}</td>
                                    <td>{{ $employee->Address }}</td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editEmployeeModal-{{ $employee->id }}">Edit</button>

                                        <!-- Delete Button -->
                                        <form method="POST" action="{{ route('Employees.destroy', $employee->id) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this employee?')">Delete</button>
                                        </form>
                                        <!-- View Button -->
                                        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewEmployeeModal-{{ $employee->id }}">View</button>
                                    </td>
                                </tr>

                                <!-- Modal for Editing an Employee -->
                                <!-- Modal for Editing an Employee -->
                                <div class="modal fade" id="editEmployeeModal-{{ $employee->id }}" tabindex="-1" aria-labelledby="editEmployeeModalLabel-{{ $employee->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editEmployeeModalLabel-{{ $employee->id }}">Edit Employee</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('Employees.update', $employee->id) }}">
                                                    @csrf
                                                    @method('PUT')

                                                    <!-- Name Row -->
                                                    <div class="row">
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

                                                    <!-- QR Code Section -->
                                                    <div class="mb-3">
                                                        <label for="QRcode-{{ $employee->id }}" class="form-label">QR Code</label>
                                                        <input type="text" class="form-control" id="QRcode-{{ $employee->id }}" name="QRcode" value="{{ $employee->QRcode }}">
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
                                                <h5 class="modal-title" id="viewEmployeeModalLabel-{{ $employee->id }}">View Employee</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Employee Details -->
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

                                                <div class="mb-3">
                                                    <label class="form-label">QR Code</label>
                                                    <input type="text" class="form-control" value="{{ $employee->QRcode }}" readonly>
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
                    <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
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
                                <input type="email" class="form-control" id="Email" name="Email" required>
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
                            <div class="col-md-4 mb-3">
                                <label for="DepartmentID" class="form-label">Department</label>
                                <select class="form-select" id="DepartmentID" name="DepartmentID" required>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->DepartmentName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="PositionID" class="form-label">Position</label>
                                <select class="form-select" id="PositionID" name="PositionID" required>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->id }}">{{ $position->PositionName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="BaseSalary" class="form-label">Base Salary</label>
                                <input type="number" class="form-control" id="BaseSalary" name="BaseSalary" required>
                            </div>
                        </div>

                        <!-- QR Code Section -->
                        <div class="mb-3">
                            <label for="QRcode" class="form-label">QR Code</label>
                            <input type="text" class="form-control" id="QRcode" name="QRcode" placeholder="Enter QR Code" >
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</x-layout>
