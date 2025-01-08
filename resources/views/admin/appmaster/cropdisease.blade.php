@extends('admin.common.layout')

@section('title', 'Crop Disease Management')

@section('custom_header')
@endsection

@section('main')
    <div id="successAlert" class="alert alert-success alert-dismissible fade d-none" role="alert">
        <span class="alert-message"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">Crop Diseases Management</h5>
            <div>
                <a href="{{ route('admin.appmaster.createcropdisease') }}" class="btn btn-outline-success">
                    <i class="tf-icons bx bx-plus-medical"></i>Add New Disease
                </a>
            </div>
        </div>

        <div class="table-responsive text-nowrap px-4">
            <table class="table" id="tblCropDesease">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Crop Disease</th>
                        <th>Crop Disease Assamese</th>
                        <th>Crop Type</th>
                        <th>Scientific Name</th>
                        <th>Folder Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($data as $index => $item)
                        <tr data-disease-cd={{ $item->disease_cd }} data-original-index="{{ $index + 1 }}">
                            <td>{{ $index + 1 }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->disease_name }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->disease_name_as }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->crop_type_desc }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->scientific_name }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->folder_name }}</td>
                            <td>

                                <a href="#" class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal"
                                    data-bs-target="#editModal"
                                    data-id="{{ $item->disease_cd }}"
                                    data-disease-name="{{ $item->disease_name }}"
                                    data-disease-name-as="{{ $item->disease_name_as }}"
                                    data-crop-type="{{ $item->crop_type_cd }}"
                                    data-scientific-name = "{{ $item->scientific_name }}"
                                    data-folder-name = "{{ $item->folder_name }}">
                                    <i class="tf-icons bx bx-edit"></i> Edit
                                </a>

                                <a href="#" class="btn btn-sm btn-outline-danger delete-btn" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-disease_cd="{{ $item->disease_cd }}">
                                    <i class="tf-icons bx bx-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No data found</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>


    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Crop Disease</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST" action="{{ route('admin.appmaster.cropdisease.update') }}"
                    autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="col mb-3">
                            <label for="crop_type_cd" class="form-label">Select Crop</label>
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

                        <input type="hidden" name="disease_cd" id="diseaseCd">

                        <div class="mb-3">
                            <label for="diseaseName" class="form-label">Disease Name</label>
                            <input type="text" class="form-control" id="diseaseName" name="disease_name"
                                placeholder="Enter Disease Name">
                            <div class="invalid-feedback disease-name-feedback" style="display: none;">
                                Please provide a Disease Name.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="diseaseNameAs" class="form-label">Disease Name Assamese</label>
                            <input type="text" class="form-control" id="diseaseNameAs" name="disease_name_as"
                                placeholder="Enter Disease Name (Assamese)">
                        </div>
                        <div class="mb-3">
                            <label for="scientificName" class="form-label">Scientific Name</label>
                            <input type="text" class="form-control" id="scientificName" name="scientific_name"
                                placeholder="Enter Scientific Name">
                        </div>
                        <div class="mb-3">
                            <label for="folderName" class="form-label">Folder Name</label>
                            <input type="text" class="form-control" id="folderName" name="folder_name"
                                placeholder="Enter Folder Name">
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
                    <h5 class="modal-title" id="deleteModalLabel">Delete Disease</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteForm" method="POST" action="{{ route('admin.appmaster.cropdisease.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Are you sure you want to delete this disease?</p>
                        <input type="hidden" name="disease_cd" id="deleteId">
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
                  
            $('#tblCropDesease').DataTable();

            $('#editModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var diseaseCd = button.data('id');
                var diseaseName = button.data('disease-name');
                var diseaseNameAs = button.data('disease-name-as');
                var scientificName = button.data('scientific-name');
                var folderName = button.data('folder-name');
                var cropTypeCd = button.data('crop-type');

                var modal = $(this);
                modal.find('#diseaseCd').val(diseaseCd);
                modal.find('#diseaseName').val(diseaseName);
                modal.find('#diseaseNameAs').val(diseaseNameAs);
                modal.find('#scientificName').val(scientificName);
                modal.find('#folderName').val(folderName);
                modal.find('#crop_type_cd').val(cropTypeCd);
            });

            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                var isValid = true;
                var diseaseName = $('#diseaseName').val().trim();
                var diseaseNameAs = $('#diseaseNameAs').val().trim();
                var scientificName = $('#scientificName').val().trim();
                var folderName = $('#folderName').val().trim();
                var diseaseCd = $('#diseaseCd').val().trim();
                var cropTypeCd = $('#crop_type_cd').val().trim();
                var form = $(this);

                $('#diseaseName').removeClass('is-invalid');
                $('#crop_type_cd').removeClass('is-invalid');
                $('.invalid-feedback').hide();

                if (diseaseName === '') {
                    $('#diseaseName').addClass('is-invalid');
                    $('.invalid-feedback').filter('.disease-name-feedback').show();
                    isValid = false;
                }

                if (cropTypeCd === '') {
                    $('#crop_type_cd').addClass('is-invalid');
                    $('.invalid-feedback').filter('.crop-type-feedback').show(); // Add feedback for crop type
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

                                console.log(response.cropTypes);

                                // console.log(response.cropTypes.crop_type_desc);

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
                                    `#tblCropDesease tbody tr[data-disease-cd="${response.updatedDisease.disease_cd}"]`
                                );

                                targetRow.find('td:nth-child(2)').text(response.updatedDisease.disease_name);
                                targetRow.find('td:nth-child(3)').text(response.updatedDisease.disease_name_as);
                                targetRow.find('td:nth-child(4)').text(response.cropTypes);
                                targetRow.find('td:nth-child(5)').text(response.updatedDisease.scientific_name);
                                targetRow.find('td:nth-child(6)').text(response.updatedDisease.folder_name);

                                const editButton = targetRow.find('.edit-btn');
                                editButton.data('id', response.updatedDisease.disease_cd);
                                editButton.data('disease-name', response.updatedDisease.disease_name);
                                editButton.data('disease-name-as', response.updatedDisease.disease_name_as);
                                editButton.data('folder-name', response.updatedDisease.folder_name);
                                editButton.data('crop-type', response.updatedDisease.crop_type_cd);
                                editButton.data('scientific-name', response.updatedDisease.scientific_name);


                                targetRow.data('original-index', response.updatedDisease.disease_cd);
                                var table = $('#tblCropDesease').DataTable();
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
                var disease_cd = button.data('disease_cd');

                var modal = $(this);
                modal.find('#deleteId').val(disease_cd);
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
                            var rowToDelete = $('#tblCropDesease tbody tr[data-disease-cd="' +
                                response.disease_cd + '"]');
                            var table = $('#tblCropDesease').DataTable();

                            table.row(rowToDelete).remove().draw(false);
                            table.draw(false);

                            updateSerialNumbers(table);

                            $('#successAlert .alert-message').text(response.message);
                            $('#successAlert').removeClass('d-none').addClass('show');

                            setTimeout(function() {
                                $('#successAlert').removeClass('show').addClass(
                                    'd-none');
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
