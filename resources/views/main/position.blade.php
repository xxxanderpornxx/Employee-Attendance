<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="{{ asset('css/position.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <style>
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
        </style>
    </head>

    <body>
<x-layout>

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
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="mb-0">Position</h2>
                            <!-- Add Button for Position -->
                            <button class="addbutton " data-bs-toggle="modal" data-bs-target="#addPositionModal">
                                <i class="bi bi-plus"></i> Add Position
                            </button>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <!-- Scrollable Table -->
                                <div class="table-responsive">
                                    <table id="positionTable" class="table table-striped table-bordered">
                                        <colgroup>
                                            <col style="width:5%">
                                            <col style="width:50%">
                                            <col style="width: 25%">
                                        </colgroup>
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
                                                        <button class="btn btn-warning edit-position-btn"
                                                                data-id="{{ $position->id }}"
                                                                data-name="{{ $position->PositionName }}">
                                                            <i class="bi bi-pencil text-white"></i>
                                                        </button>
                                                        <form method="POST" action="{{ route('positions.destroy', $position->id) }}" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger delete-position"><i class="bi bi-trash"></i> </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Department Table -->
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="mb-0">Department</h2>
                            <!-- Add Button for Department -->
                            <button class="addbutton" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                                <i class="bi bi-plus"></i> Add Department
                            </button>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <!-- Scrollable Table -->
                                <div class="table-responsive">
                                    <table id="departmentTable" class="table table-striped table-bordered">
                                        <colgroup>
                                            <col style="width:5%">
                                            <col style="width: 50%">
                                            <col style="width: 25%">
                                        </colgroup>
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
                                                        <button class="btn btn-warning edit-department-btn"
                                                                data-id="{{ $department->id }}"
                                                                data-name="{{ $department->DepartmentName }}">
                                                            <i class="bi bi-pencil text-white"></i>
                                                        </button>

                                                        <form method="POST" action="{{ route('departments.destroy', $department->id) }}" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger delete-department"><i class="bi bi-trash"></i> </button>
                                                        </form>
                                                    </td>
                                                </tr>
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
                            <form id="addPositionForm" method="POST" action="{{ route('positions.store') }}">
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
                            <form id="addDepartmentForm" method="POST" action="{{ route('departments.store') }}">
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

            <!-- Position Edit Modal (single, outside the loop) -->
            <div class="modal fade" id="editPositionModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Position</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editPositionForm" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="editPositionName" class="form-label">Position Name</label>
                                    <input type="text" class="form-control" id="editPositionName" name="PositionName" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Edit Modal (single, outside the loop) -->
            <div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Department</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editDepartmentForm" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="editDepartmentName" class="form-label">Department Name</label>
                                    <input type="text" class="form-control" id="editDepartmentName" name="DepartmentName" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    // Initialize DataTables only once
    const positionTable = $('#positionTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "lengthChange": true,
        "pageLength": 10
    });

    const departmentTable = $('#departmentTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "lengthChange": true,
        "pageLength": 10
    });

    // Handle Position Delete
    $('.delete-position').click(function(e) {
        e.preventDefault();
        const form = $(this).closest('form');

        Swal.fire({
            title: 'Delete Position?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Handle Department Delete
    $('.delete-department').click(function(e) {
        e.preventDefault();
        const form = $(this).closest('form');

        Swal.fire({
            title: 'Delete Department?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Handle Form Submissions
    $('#addPositionForm, #addDepartmentForm, #editPositionForm, #editDepartmentForm').submit(function(e) {
        e.preventDefault();
        const form = $(this);
        const isEdit = form.attr('id') === 'editPositionForm' || form.attr('id') === 'editDepartmentForm';
        const type = form.attr('id') === 'editPositionForm' || form.attr('id') === 'addPositionForm' ? 'Position' : 'Department';

        Swal.fire({
            title: `${isEdit ? 'Updating' : 'Adding'} ${type}...`,
            html: 'Please wait...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: `${type} ${isEdit ? 'updated' : 'added'} successfully!`,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseJSON?.message || 'Something went wrong!'
                });
            }
        });
    });

    // Show success message if exists in session
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 1500
        });
    @endif

    // Populate Edit Modals
    $('.edit-position-btn').click(function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#editPositionForm').attr('action', `/positions/${id}`);
        $('#editPositionName').val(name);
    });

    $('.edit-department-btn').click(function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#editDepartmentForm').attr('action', `/departments/${id}`);
        $('#editDepartmentName').val(name);
    });

    // Handle Position Edit
    $('.edit-position-btn').on('click', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#editPositionName').val(name);
        $('#editPositionForm').attr('action', `/positions/${id}`);
        $('#editPositionModal').modal('show');
    });

    // Handle Department Edit
    $('.edit-department-btn').on('click', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#editDepartmentName').val(name);
        $('#editDepartmentForm').attr('action', `/departments/${id}`);
        $('#editDepartmentModal').modal('show');
    });
});
</script>
</x-layout>
</body>

</html>
