<x-layout>
    <head>
        <link rel="stylesheet" href="{{ asset('css/position.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    </head>
    <div class="col-12">
        <div class="position-page">
            <div class="row p-3 pb-5">
                <div class="col-auto">

                </div>
            </div>

            <!-- Row for Position and Department Tables -->
            <div class="row">
                <!-- Position Table -->
                <div class="col-md-6">
                    <h2 class="text-center mb-3">Position</h2>
                    <div class="text-center mb-3">
                        <!-- Add Button for Position -->
                        <button class="addbutton" data-bs-toggle="modal" data-bs-target="#addPositionModal">
                            <i class="bi bi-plus"></i> Add Position
                        </button>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="searchBar" placeholder="Search positions...">
                                </div>
                                <div class="col-md-6 text-end">
                                    <select class="form-select w-auto d-inline-block" id="sortBar">
                                        <option value="id_asc">Sort by ID (Ascending)</option>
                                        <option value="id_desc">Sort by ID (Descending)</option>
                                        <option value="name_asc">Sort by Name (A-Z)</option>
                                        <option value="name_desc">Sort by Name (Z-A)</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Scrollable Table -->
                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="table-primary">
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($positions as $position)
                                            <tr>
                                                <td>{{ $position->id }}</td>
                                                <td>{{ $position->PositionName }}</td>
                                                <td>
                                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editPositionModal-{{ $position->id }}">Edit</button>
                                                    <form method="POST" action="{{ route('positions.destroy', $position->id) }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this position?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="editPositionModal-{{ $position->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Position</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST" action="{{ route('positions.update', $position->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="mb-3">
                                                                    <label for="PositionName-{{ $position->id }}" class="form-label">Position Name</label>
                                                                    <input type="text" class="form-control" id="PositionName-{{ $position->id }}" name="PositionName" value="{{ $position->PositionName }}" required>
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

                <!-- Department Table -->
                <div class="col-md-6">
                    <h2 class="text-center mb-3">Department</h2>
                    <div class="text-center mb-3">
                        <!-- Add Button for Department -->
                        <button class="addbutton" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                            <i class="bi bi-plus"></i> Add Department
                        </button>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="searchBarDept" placeholder="Search departments...">
                                </div>
                                <div class="col-md-6 text-end">
                                    <select class="form-select w-auto d-inline-block" id="sortBarDept">
                                        <option value="id_asc">Sort by ID (Ascending)</option>
                                        <option value="id_desc">Sort by ID (Descending)</option>
                                        <option value="name_asc">Sort by Name (A-Z)</option>
                                        <option value="name_desc">Sort by Name (Z-A)</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Scrollable Table -->
                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="table-primary">
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($departments as $department)
                                            <tr>
                                                <td>{{ $department->id }}</td>
                                                <td>{{ $department->DepartmentName }}</td>
                                                <td>
                                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editDepartmentModal-{{ $department->id }}">Edit</button>
                                                    <form method="POST" action="{{ route('departments.destroy', $department->id) }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this department?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="editDepartmentModal-{{ $department->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Department</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST" action="{{ route('departments.update', $department->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="mb-3">
                                                                    <label for="DepartmentName-{{ $department->id }}" class="form-label">Department Name</label>
                                                                    <input type="text" class="form-control" id="DepartmentName-{{ $department->id }}" name="DepartmentName" value="{{ $department->DepartmentName }}" required>
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
            </div>
        </div>

        <!-- Add Position Modal -->
        <div class="modal fade" id="addPositionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Position</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('positions.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="PositionName" class="form-label">Position Name</label>
                                <input type="text" class="form-control" id="PositionName" name="PositionName" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Department Modal -->
        <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Department</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('departments.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="DepartmentName" class="form-label">Department Name</label>
                                <input type="text" class="form-control" id="DepartmentName" name="DepartmentName" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</x-layout>
