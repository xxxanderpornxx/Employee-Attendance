<x-layout>
    <head>
        <link rel="stylesheet" href="{{ asset('css/position.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> <!-- Include Bootstrap Icons -->
    </head>
    <div class="position-page">
        <div class="row p-3 pb-5">
            <div class="col-auto">
                <h1 class="pb-3">Positions</h1>
                <button class="addbutton" data-bs-toggle="modal" data-bs-target="#addPositionModal">
                    <i class="bi bi-plus"></i> Add Position
                </button>
            </div>
        </div>
        <div class="row">
            <div>
                <table class="table table-striped table-bordered">

                    <colgroup>
                        <col style="width:20%;">
                        <col style="width: 50%;">
                        <col style="width: 20%;">
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
                                    <!-- Edit Button -->
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editPositionModal-{{ $position->id }}">Edit</button>

                                    <!-- Delete Button -->
                                    <form method="POST" action="{{ route('positions.destroy', $position->id) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this position?')">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal for Editing a Position -->
                            <div class="modal fade" id="editPositionModal-{{ $position->id }}" tabindex="-1" aria-labelledby="editPositionModalLabel-{{ $position->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editPositionModalLabel-{{ $position->id }}">Edit Position</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

        <!-- Modal for Adding a New Position -->
        <div class="modal fade" id="addPositionModal" tabindex="-1" aria-labelledby="addPositionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPositionModalLabel">Add New Position</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</x-layout>
