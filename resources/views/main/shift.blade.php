<x-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    .addshiftbutton {
    background-color: #34abc7; /* Set the button color */
    color: white; /* Set the text color */
    border: none; /* Remove the border */
    border-radius: 4px; /* Add rounded corners */
    padding: 10px 20px; /* Adjust padding for better appearance */
    font-size: 16px; /* Adjust font size */
    font-weight: bold; /* Make the text bold */
    cursor: pointer; /* Change cursor to pointer */
}

    .addshiftbutton:hover {
    background-color: #2a94a3;
    color: #e0f7fa;
}
    </style>
    <div class="">
        <div class="row p-3 pb-5">
            <div class="col-auto">
                <h1 class="pb-3">Shifts</h1>
                <button class="addshiftbutton" data-bs-toggle="modal" data-bs-target="#addShiftModal">
                    <i class="bi bi-plus"></i> + Add Shift
                </button>
            </div>
        </div>
        <div class="row">
            <div>
                <!-- Scrollable Table -->
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-striped table-bordered">
                        <colgroup>
                            <col style="width:15%;">
                            <col style="width: 30%;">
                            <col style="width: 30%;">
                            <col style="width: 10%;">
                        </colgroup>
                        <thead>
                            <tr class="table-primary">
                                <th>Shift Id</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shifts as $shift)
                                <tr>
                                    <td>{{ $shift->id }}</td>
                                    <td>{{ $shift->StartTime }}</td>
                                    <td>{{ $shift->EndTime }}</td>
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
                                                        <input type="time" class="form-control" id="StartTime-{{ $shift->id }}" name="StartTime" value="{{ $shift->StartTime }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="EndTime-{{ $shift->id }}" class="form-label">End Time</label>
                                                        <input type="time" class="form-control" id="EndTime-{{ $shift->id }}" name="EndTime" value="{{ $shift->EndTime }}" required>
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
</x-layout>
