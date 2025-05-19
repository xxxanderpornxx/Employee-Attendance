<!DOCTYPE html>
<html lang="en">

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
        <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/shift.css') }}">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <title>Shifts</title>
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

            .modal {
                animation-duration: 0.3s;
                transition: opacity 0.3s ease;
            }

            .modal.fade .modal-dialog {
                transition: transform 0.3s ease-out;
                transform: translate(0, -50px);
            }

            .modal.show .modal-dialog {
                transform: none;
            }

            /* Remove duplicate backdrops */
            .modal-backdrop.fade.show:nth-child(2) {
                display: none;
            }

            .modal-open {
                overflow: hidden;
                padding-right: 0 !important;
            }
        </style>
    </head>

    <body>
        <x-layout>
        <div class="col-12">
            <div class="row p-3 pb-2 align-items-center">
                <div class="col">
                    <h1 class="pb-3">Shifts</h1>
                </div>
                <div class="col-auto">
                    <button class="addshiftbutton" data-bs-toggle="modal" data-bs-target="#addShiftModal">
                        <i class="bi bi-plus"></i> Add Shift
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <!-- Card for DataTable -->
                    <div class="card">
                        <div class="card-body">
                            <!-- Scrollable Table -->
                            <div class="table-responsive">
                                <table id="shiftTable" class="table table-striped table-bordered">
                                    <colgroup>
                                        <col style="width:05%;">
                                        <col style="width:15%;">
                                        <col style="width: 30%;">
                                        <col style="width: 30%;">
                                        <col style="width: 13%;">
                                    </colgroup>
                                    <thead>
                                        <tr class="table-primary">
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($shifts as $shift)
                                            <tr>
                                                <td>{{ $shift->id }}</td>
                                                <td>{{ $shift->ShiftName }}</td>
                                                <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $shift->StartTime)->format('h:i A') }}</td>
                                                <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $shift->EndTime)->format('h:i A') }}</td>
                                                <td>
                                                    <!-- Edit Button -->
                                                    <button class="btn btn-warning edit-shift-btn"
                                                            data-id="{{ $shift->id }}"
                                                            data-name="{{ $shift->ShiftName }}"
                                                            data-start="{{ \Carbon\Carbon::createFromFormat('H:i:s', $shift->StartTime)->format('H:i') }}"
                                                            data-end="{{ \Carbon\Carbon::createFromFormat('H:i:s', $shift->EndTime)->format('H:i') }}">
                                                        <i class="bi bi-pencil" style="color: white"></i>
                                                    </button>

                                                    <!-- Delete Button -->
                                                    <form id="delete-form-{{ $shift->id }}" method="POST" action="{{ route('shifts.destroy', $shift->id) }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger" onclick="confirmDelete('delete-form-{{ $shift->id }}')">
                                                            <i class="bi bi-trash" style="text-white"></i>
                                                        </button>
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

        <!-- Modal for Adding a New Shift -->
        <div class="modal fade" id="addShiftModal" tabindex="-1" aria-labelledby="addShiftModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addShiftModalLabel">Add New Shift</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('shifts.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="ShiftName" class="form-label">Shift Name</label>
                                <input type="text" class="form-control" id="ShiftName" name="ShiftName" required>
                            </div>
                            <div class="mb-3">
                                <label for="StartTime" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="StartTime" name="StartTime" required>
                            </div>
                            <div class="mb-3">
                                <label for="EndTime" class="form-label">End Time</label>
                                <input type="time" class="form-control" id="EndTime" name="EndTime" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Single Edit Shift Modal -->
        <div class="modal fade" id="editShiftModal" tabindex="-1" aria-labelledby="editShiftModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editShiftModalLabel">Edit Shift</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editShiftForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="editShiftName" class="form-label">Shift Name</label>
                                <input type="text" class="form-control" id="editShiftName" name="ShiftName" required>
                            </div>
                            <div class="mb-3">
                                <label for="editStartTime" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="editStartTime" name="StartTime" required>
                            </div>
                            <div class="mb-3">
                                <label for="editEndTime" class="form-label">End Time</label>
                                <input type="time" class="form-control" id="editEndTime" name="EndTime" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
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
                // Initialize DataTable
                $('#shiftTable').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "info": true
                });

                // Function to confirm deletion
                window.confirmDelete = function(formId) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(formId).submit();
                        }
                    });
                }

                // Function to populate edit modal
                window.populateEditModal = function(shift) {
                    $('#editShiftForm').attr('action', `/shifts/${shift.id}`);
                    $('#editShiftName').val(shift.ShiftName);
                    $('#editStartTime').val(shift.StartTime);
                    $('#editEndTime').val(shift.EndTime);
                }

                // Handle Edit Shift Button Click
             $('.edit-shift-btn').on('click', function() {
    const id = $(this).data('id');
    const name = $(this).data('name');
    const startTime = $(this).data('start');
    const endTime = $(this).data('end');

    // Set form action with the correct route
    $('#editShiftForm').attr('action', '{{ route("shifts.index") }}/' + id);
    // Or use this alternative if the above doesn't work
    // $('#editShiftForm').attr('action', '/shifts/' + id);

    // Fill form fields
    $('#editShiftName').val(name);
    $('#editStartTime').val(startTime);
    $('#editEndTime').val(endTime);

    // Show modal
    $('#editShiftModal').modal('show');
});
            });
        </script>
</x-layout>
</body>

</html>
