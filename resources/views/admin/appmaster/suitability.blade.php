@extends('admin.common.layout')

@section('title', 'Suitability Management')

@section('custom_header')
@endsection

@section('main')
    <div id="successAlert" class="alert alert-success alert-dismissible fade d-none" role="alert">
        <span class="alert-message"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">Suitability Management</h5>
            <div>
                <a href="{{ route('admin.appmaster.createsuitability') }}" class="btn btn-outline-success">
                    <i class="tf-icons bx bx-plus-medical"></i>Add Suitability
                </a>
            </div>
        </div>

        <div class="table-responsive text-nowrap px-4">
            <table class="table" id="tblCropType">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Soil</th>
                        <th>Soil Assamese</th>
                        <th>Sowing Time</th>
                        <th>Sowing Time Assamese</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($data as $index => $item)
                        <tr data-id={{ $item->id }} data-original-index="{{ $index + 1 }}">
                            <td>{{ $index + 1 }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->soil }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->soil_as }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->sowing_time }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->sowing_time_as }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal"
                                    data-bs-target="#editModal"
                                    data-id="{{ $item->id }}"
                                    data-crop-type="{{ $item->crop_type_cd }}"
                                    data-crop-name="{{ $item->crop_name_cd }}"
                                    data-soil="{{ $item->soil }}"
                                    data-soil-as="{{ $item->soil_as }}"
                                    data-sowing-time="{{ $item->sowing_time }}"
                                    data-sowing-time-as="{{ $item->sowing_time_as }}">
                                    <i class="tf-icons bx bx-edit"></i> Edit
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-danger delete-btn" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-id="{{ $item->id }}">
                                    <i class="tf-icons bx bx-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No data found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Suitability</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST" action="{{ route('admin.appmaster.suitability.update') }}"
                    autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="col mb-3">
                            <label for="crop_type_cd" class="form-label">Select Crop Type</label>
                            <select class="form-select @error('crop_type_cd') is-invalid @enderror" id="crop_type_cd"
                                name="crop_type_cd">
                                <option value="">Select Crop Type</option>
                                @foreach ($cropTypes as $id => $desc)
                                    <option value="{{ $id }}">{{ $desc }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback crop-type-feedback" style="display: none;">
                                Please select a Crop Type.
                            </div>
                        </div>
                        <div class="col mb-3">
                            <label for="crop_name_cd" class="form-label">Select Crop Name</label>
                            <select class="form-select @error('crop_name_cd') is-invalid @enderror" id="crop_name_cd"
                                name="crop_name_cd">
                                <option value="">Select Crop Name</option>
                            </select>
                            <div class="invalid-feedback crop-name-feedback" style="display: none;">
                                Please select a Crop Name.
                            </div>
                        </div>
                        <input type="hidden" name="id" id="id">
                        <div class="mb-3">
                            <label for="soil" class="form-label">Soil</label>
                            <textarea rows="4" class="form-control" id="soil" name="soil" placeholder="Enter Soil"></textarea>
                            <div class="invalid-feedback soil-feedback" style="display: none;">
                                Please provide Soil
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="soilAs" class="form-label">Soil Assamese</label>
                            <textarea rows="4" class="form-control" id="soilAs" name="soil_as" placeholder="Enter Soil (Assamese)"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="sowingTime" class="form-label">Sowing Time</label>
                            <textarea rows="4" class="form-control" id="sowingTime" name="sowing_time" placeholder="Enter Sowing Time"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="sowingTimeAs" class="form-label">Sowing Time Assamese</label>
                            <textarea rows="4" class="form-control" id="sowingTimeAs" name="sowing_time_as"
                                placeholder="Enter Sowing Time (Assamese)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Suitability</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteForm" method="POST" action="{{ route('admin.appmaster.suitability.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Are you sure you want to delete this suitability?</p>
                        <input type="hidden" name="id" id="deleteId">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        $(document).ready(function() {
            const allElements = document.querySelectorAll('*');
                  allElements.forEach(el => {
                      el.style.fontSize = '14px';
                  });
            $('#tblCropType').DataTable();

            $('#editModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var cropTypeCd = button.data('crop-type');
                var cropNameCd = button.data('crop-name');
                var soil = button.data('soil');
                var soilAs = button.data('soil-as');
                var sowingTime = button.data('sowing-time');
                var sowingTimeAs = button.data('sowing-time-as');

                var modal = $(this);
                modal.find('#id').val(id);
                modal.find('#soil').val(soil);
                modal.find('#soilAs').val(soilAs);
                modal.find('#sowingTime').val(sowingTime);
                modal.find('#sowingTimeAs').val(sowingTimeAs);
                modal.find('#crop_type_cd').val(cropTypeCd);

                // Load crop names
                $.ajax({
                    url: '{{ route('admin.appmaster.getCropNames') }}',
                    type: 'GET',
                    data: {
                        crop_type_cd: cropTypeCd
                    },
                    success: function(data) {
                        var $cropNameSelect = $('#crop_name_cd');
                        $cropNameSelect.empty(); // Clear existing options
                        $cropNameSelect.append(
                            '<option value="">Select Crop Name</option>'); // Default option

                        $.each(data, function(cropNameCd, cropNameDesc) {
                            $cropNameSelect.append(new Option(cropNameDesc,
                                cropNameCd));
                        });

                        $cropNameSelect.val(cropNameCd); // Set selected value
                    }
                });
            });

            $('#crop_type_cd').on('change', function() {
                var cropTypeCd = $(this).val();
                var $cropNameSelect = $('#crop_name_cd');
                $cropNameSelect.empty();
                $cropNameSelect.append('<option value="">Select Crop Name</option>');

                if (cropTypeCd) {
                    $.ajax({
                        url: '{{ route('admin.appmaster.getCropNames') }}',
                        type: 'GET',
                        data: {
                            crop_type_cd: cropTypeCd
                        },
                        success: function(data) {
                            var sortedData = Object.entries(data).sort(function(a, b) {
                                // Sort by the crop description (a[1] and b[1])
                                return a[1].localeCompare(b[1]);
                            });

                            // Iterate through the sorted data and append to the select
                            $.each(sortedData, function(index, entry) {
                                var cropNameCd = entry[0]; // crop code (key)
                                var cropNameDesc = entry[1]; // crop description (value)

                                // Append the sorted option to the select dropdown
                                $cropNameSelect.append(new Option(cropNameDesc,
                                    cropNameCd));
                            });
                        }
                    });
                }
            });

            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                var isValid = true;
                var soil = $('#soil').val().trim();
                var soilAs = $('#soilAs').val().trim();
                var sowingTime = $('#sowingTime').val().trim();
                var sowingTimeAs = $('#sowingTimeAs').val().trim();
                var cropTypeCd = $('#crop_type_cd').val().trim();
                var cropNameCd = $('#crop_name_cd').val().trim();
                var form = $(this);

                $('#soil').removeClass('is-invalid');
                $('#crop_type_cd').removeClass('is-invalid');
                $('#crop_name_cd').removeClass('is-invalid');
                $('.invalid-feedback').hide();

                if (soil === '') {
                    $('#soil').addClass('is-invalid');
                    $('.invalid-feedback.soil-feedback').show();
                    isValid = false;
                }

                if (cropTypeCd === '') {
                    $('#crop_type_cd').addClass('is-invalid');
                    $('.invalid-feedback.crop-type-feedback').show();
                    isValid = false;
                }

                if (cropNameCd === '') {
                    $('#crop_name_cd').addClass('is-invalid');
                    $('.invalid-feedback.crop-name-feedback').show();
                    isValid = false;
                }

                if (isValid) {
                    $.ajax({
                        url: form.attr('action'),
                        method: 'PUT',
                        data: form.serialize(),
                        dataType: 'json',
                        success: function(response) {

                            if (response.success) {

                                console.log(response);

                                $('#editModal').modal('hide');
                                $('#successAlert .alert-message').text(
                                    response.message);
                                $('#successAlert').removeClass('d-none')
                                    .addClass('show');

                                setTimeout(function() {
                                    $('#successAlert')
                                        .removeClass('show')
                                        .addClass('d-none');
                                }, 5000);

                                const targetRow = $(
                                    `#tblCropType tbody tr[data-id="${response.cropSuitability.id}"]`
                                );

                                targetRow.find('td:nth-child(2)').text(response.cropSuitability.soil);
                                targetRow.find('td:nth-child(3)').text(response.cropSuitability.soil_as);
                                targetRow.find('td:nth-child(4)').text(response.cropSuitability.sowing_time);
                                targetRow.find('td:nth-child(5)').text(response.cropSuitability.sowing_time_as);

                                const editButton = targetRow.find('.edit-btn');
                                editButton.data('id', response.cropSuitability.id);
                                editButton.data('crop-type', response.cropSuitability.crop_type_cd);
                                editButton.data('crop-name', response.cropSuitability.crop_name_cd);
                                editButton.data('soil', response.cropSuitability.soil);
                                editButton.data('soil-as', response.cropSuitability.soil_as);
                                editButton.data('sowing-time', response.cropSuitability.sowing_time);
                                editButton.data('sowing-time-as', response.cropSuitability.sowing_time_as);


                                targetRow.data('original-index', response.cropSuitability.id);
                                var table = $('#tblCropType').DataTable();
                                table.draw(false);
                                updateSerialNumbers(table);
                            } else {
                                alert('Failed to update variety.');
                            }
                        },
                        error: function(xhr) {
                            console.error('Error updating variety:', xhr
                                .responseText);
                            alert(
                                'There was an error updating the variety.');
                        }
                    });
                }
            });

            function updateSerialNumbers(table) {
                table.rows().every(function(index) {
                    var row = this.node();
                    var serialNumber = index + 1;
                    $(row).find('td:first').text(serialNumber);
                });
            }

            $('#deleteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');

                var modal = $(this);
                modal.find('#deleteId').val(id);
            });


            $('#deleteForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);

                $.ajax({
                    url: form.attr('action'),
                    method: 'DELETE',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            var rowToDelete = $('#tblCropType tbody tr[data-id="' +
                                response.id + '"]');
                            var table = $('#tblCropType').DataTable();

                            table.row(rowToDelete).remove().draw(false);
                            table.draw(false);

                            updateSerialNumbers(table);

                            $('#successAlert .alert-message').text(response.message);
                            $('#successAlert').removeClass('d-none').addClass('show');

                            setTimeout(function() {
                                $('#successAlert').removeClass('show').addClass('d-none');
                            }, 5000);

                            $('#deleteModal').modal('hide');
                        } else {
                            console.error('Failed to delete variety:', response.message);
                            alert('Failed to delete the variety.');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error deleting variety:', xhr.responseText);
                        alert('There was an error deleting the variety.');
                    }
                });
            });


            function updateSerialNumbers(table) {
                table.rows().every(function(index) {
                    var row = this.node();
                    var serialNumber = index + 1;
                    $(row).find('td:first').text(serialNumber);
                });
            }

        });
    </script>
@endsection
