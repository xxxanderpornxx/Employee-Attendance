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
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <colgroup>
                            <col style="width:4%;">
                            <col style="width: 20%;">
                            <col style="width: 30%;">
                            <col style="width: 5%;">
                            <col style="width: 5%;">
                            <col style="width: 20%;">
                            <col style="width: 20%;">
                        </colgroup>
                        <thead>
                            <tr class="table-primary">
                                <th>Id</th>
                                <th>Name</th>
                                <th>Department|Position</th>
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
                                    <td>{{ optional($employee->position->department)->DepartmentName ?? 'N/A' }} | {{ optional($employee->position)->PositionName ?? 'N/A' }}</td>
                                    <td>{{ $employee->sex }}</td>
                                    <td>{{ \Carbon\Carbon::parse($employee->date_of_birth)->age }}</td>
                                    <td>{{ $employee->address }}</td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editEmployeeModal-{{ $employee->id }}">Edit</button>

                                        <!-- Delete Button -->
                                        <form method="POST" action="{{ route('employees.destroy', $employee->id) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this employee?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal for Editing an Employee -->
                                <div class="modal fade" id="editEmployeeModal-{{ $employee->id }}" tabindex="-1" aria-labelledby="editEmployeeModalLabel-{{ $employee->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editEmployeeModalLabel-{{ $employee->id }}">Edit Employee</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('employees.update', $employee->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <label for="name-{{ $employee->id }}" class="form-label">Name</label>
                                                        <input type="text" class="form-control" id="name-{{ $employee->id }}" name="name" value="{{ $employee->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email-{{ $employee->id }}" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="email-{{ $employee->id }}" name="email" value="{{ $employee->email }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="position-{{ $employee->id }}" class="form-label">Position</label>
                                                        <select class="form-select" id="position-{{ $employee->id }}" name="position_id" required>
                                                            @foreach ($positions as $position)
                                                                <option value="{{ $position->id }}" {{ $employee->position_id == $position->id ? 'selected' : '' }}>
                                                                    {{ $position->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </form>
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
                    <form method="POST" action="{{ route('employees.store') }}">
                        @csrf
                        <!-- Name Row -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="FirstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="FirstName" name="FirstName" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="MiddleName" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="MiddleName" name="MiddleName" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="LastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="LastName" name="LastName" required>
                            </div>
                        </div>

                        <!-- Sex, Date of Birth, and Address Row -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="sex" class="form-label">Sex</label>
                                <select class="form-select" id="sex" name="sex" required>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="dateOfBirth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dateOfBirth" name="dateOfBirth" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                        </div>

                        <!-- Email, Contact Number, and Hire Date Row -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="hireDate" class="form-label">Hire Date</label>
                                <input type="date" class="form-control" id="hireDate" name="hireDate" required>
                            </div>
                        </div>

                        <!-- Position, Department, and Base Salary Row -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="position" class="form-label">Position</label>
                                <select class="form-select" id="position" name="position_id" required>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->id }}">{{ $position->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="department" class="form-label">Department</label>
                                <select class="form-select" id="department" name="department_id" required>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="baseSalary" class="form-label">Base Salary</label>
                                <input type="number" class="form-control" id="baseSalary" name="baseSalary" required>
                            </div>
                        </div>

                        <!-- QR Code Section -->
                        <div class="mb-3 text-center">
                            <label for="qrCode" class="form-label">QR Code</label>
                            <div>
                                <img src="{{ asset('path/to/qr-code.png') }}" alt="QR Code" class="img-fluid">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</x-layout>
