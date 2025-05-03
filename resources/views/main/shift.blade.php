<x-layout>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
        <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/shift.css') }}">
        <title>Shifts</title>
    </head>

    <div class="col-12">
        <div class="row p-3 pb-2 align-items-center">
            <div class="col">
                <h1 class="pb-3">Shifts</h1>
            </div>
            <div class="col-auto">
                <button class="addshiftbutton" data-bs-toggle="modal" data-bs-target="#addShiftModal">
                    <i class="bi bi-plus"></i> + Add Shift
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
                                    <col style="width: 10%;">
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
                                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editShiftModal-{{ $shift->id }}">Edit</button>

                                                <!-- Delete Button -->
                                                <form method="POST" action="{{ route('shifts.destroy', $shift->id) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this shift?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Modal for Editing a Shift -->
                                        <div class="modal fade" id="editShiftModal-{{ $shift->id }}" tabindex="-1" aria-labelledby="editShiftModalLabel-{{ $shift->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editShiftModalLabel-{{ $shift->id }}">Edit Shift</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="{{ route('shifts.update', $shift->id) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="mb-3">
                                                                <label for="StartTime-{{ $shift->id }}" class="form-label">Start Time</label>
                                                                <input type="time" class="form-control" id="StartTime-{{ $shift->id }}" name="StartTime" value="{{ \Carbon\Carbon::createFromFormat('H:i:s', $shift->StartTime)->format('H:i') }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="EndTime-{{ $shift->id }}" class="form-label">End Time</label>
                                                                <input type="time" class="form-control" id="EndTime-{{ $shift->id }}" name="EndTime" value="{{ \Carbon\Carbon::createFromFormat('H:i:s', $shift->EndTime)->format('H:i') }}" required>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#shiftTable').DataTable();
        });
    </script>
</x-layout>
