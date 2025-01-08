@extends('admin.common.layout')

@section('title', 'Symptom Management')

@section('custom_header')
@endsection

@section('main')
    <div id="successAlert" class="alert alert-success alert-dismissible fade d-none" role="alert">
        <span class="alert-message"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">Symptom Management</h5>
            <div>
                <a href="{{ route('admin.appmaster.createsymptom') }}" class="btn btn-outline-success">
                    <i class="tf-icons bx bx-plus-medical"></i>Add New Symptom
                </a>
            </div>
        </div>

        <div class="px-4 py-2">
            <div class="mb-3">
                <label for="crop_type_cd" class="form-label">Select Crop Type</label>
                <select class="form-select" id="crop_type_cd">
                    <option value="">Select Crop Type</option>
                    @foreach ($cropTypes as $code => $description)
                        <option value="{{ $code }}">{{ $description }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="disease_cd" class="form-label">Select Disease</label>
                <select class="form-select" id="disease_cd">
                    <option value="">Select Disease</option>
                </select>
            </div>
        </div>

        <div class="table-responsive text-nowrap px-4" id="tableContainer" style="display: none;">
            <table class="table" id="tblCropDesease">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Symptom</th>
                        <th>Disease</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0" id="tableBody">
                    <!-- Table rows will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>


    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Symptom</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST" action="{{ route('admin.appmaster.symptom.update') }}"
                    autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="symptom" class="form-label">Symptom</label>
                            <textarea rows="4" class="form-control" id="symptom" name="symptom" placeholder="Enter symptom"></textarea>
                            <div class="invalid-feedback symptom-feedback" style="display: none;">
                                Please provide a Symptom.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="disease_cd" class="form-label">Select Disease</label>
                            <select class="form-select @error('disease_cd') is-invalid @enderror" id="disease_cd_edit"
                                name="disease_cd">
                                <option value="">Select Disease</option>
                                @foreach ($diseases as $id => $desc)
                                    <option value="{{ $id }}">{{ $desc }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback disease-feedback" style="display: none;">
                                Please select a Disease.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="language_cd" class="form-label">Select Language</label>
                            <select class="form-select @error('language_cd') is-invalid @enderror" id="language_cd"
                                name="language_cd">
                                <option value="">Select Language</option>
                                @foreach ($languages as $code => $description)
                                    <option value="{{ $code }}">{{ $description }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback language-feedback" style="display: none;">
                                Please select a Language.
                            </div>
                        </div>
                        <input type="hidden" name="id" id="id">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Symptom</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteForm" method="POST" action="{{ route('admin.appmaster.symptom.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Are you sure you want to delete this symptom?</p>
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
            $('#tblCropDesease').DataTable();

            // Diseases data from PHP to JavaScript
            var diseases = @json($diseases);

            var symptoms = @json($symptoms);

            // Populate disease dropdown based on selected crop type
            $('#crop_type_cd').on('change', function() {
                var cropTypeCd = $(this).val();
                var diseaseDropdown = $('#disease_cd');
                diseaseDropdown.empty();
                diseaseDropdown.append('<option value="">Select Disease</option>');

                if (cropTypeCd) {
                    var filteredDiseases = diseases[cropTypeCd] || [];

                    // Sort the diseases alphabetically by disease_name
                    filteredDiseases.sort(function(a, b) {
                        return a.disease_name.localeCompare(b
                            .disease_name); // Sorting in alphabetical order
                    });

                    // Append each sorted disease to the dropdown
                    $.each(filteredDiseases, function(index, disease) {
                        diseaseDropdown.append('<option value="' + disease.disease_cd + '">' +
                            disease.disease_name + '</option>');
                    });
                }
            });


            $('#disease_cd').on('change', function() {
                var selectedDiseaseCd = $(this).val();
                var tableBody = $('#tableBody');
                tableBody.empty();
                var tableContainer = $('#tableContainer');

                if (selectedDiseaseCd) {
                    var filteredSymptoms = symptoms.filter(function(item) {
                        return item.disease_cd == selectedDiseaseCd;
                    });

                    $.each(filteredSymptoms, function(index, item) {
                        tableBody.append(
                            '<tr data-id="' + item.id + '" data-original-index="' + (index + 1) + '">' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td style="overflow-wrap: break-word; white-space: normal;">' +
                            item.symptom + '</td>' +
                            '<td style="overflow-wrap: break-word; white-space: normal;">' +
                            item.disease_name + '</td>' +
                            '<td>' +
                            '<a href="#" class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' +
                            item.id + '" data-disease-cd="' + item.disease_cd +
                            '" data-symptom="' + item.symptom + '" data-language-cd="' + item
                            .language_cd + '"><i class="tf-icons bx bx-edit"></i> Edit</a>' +
                            '<a href="#" class="btn btn-sm btn-outline-danger delete-btn" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="' +
                            item.id + '"><i class="tf-icons bx bx-trash"></i></a>' +
                            '</td>' +
                            '</tr>');
                    });

                    tableContainer.show(); // Show the table
                } else {
                    tableContainer.hide(); // Hide the table if no disease selected
                }
            });

            $('#editModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var symptom = button.data('symptom');
                var diseaseCd = button.data('disease-cd');
                var languageCd = button.data('language-cd');

                var modal = $(this);
                modal.find('#id').val(id);
                modal.find('#symptom').val(symptom);
                modal.find('#language_cd').val(languageCd).trigger('change');

                // Populate disease dropdown based on the current crop type
                var cropTypeCd = $('#crop_type_cd').val(); // Assuming you have a way to get this
                var diseaseDropdown = modal.find('#disease_cd_edit');
                diseaseDropdown.empty();
                diseaseDropdown.append('<option value="">Select Disease</option>');

                if (cropTypeCd) {
                    var filteredDiseases = diseases[cropTypeCd] || [];

                    $.each(filteredDiseases, function(index, disease) {
                        var selected = disease.disease_cd == diseaseCd ? 'selected' : '';
                        diseaseDropdown.append('<option value="' + disease.disease_cd + '" ' +
                            selected + '>' + disease.disease_name + '</option>');
                    });
                }
            });



            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                var isValid = true;
                var symptom = $('#symptom').val().trim();
                var diseaseCd = $('#disease_cd_edit').val().trim();
                var languageCd = $('#language_cd').val().trim();
                var form = $(this);

                $('#symptom').removeClass('is-invalid');
                $('#disease_cd_edit').removeClass('is-invalid');
                $('#language_cd').removeClass('is-invalid');
                $('.invalid-feedback').hide();

                if (symptom === '') {
                    $('#symptom').addClass('is-invalid');
                    $('.invalid-feedback').filter('.symptom-feedback').show();
                    isValid = false;
                }

                if (diseaseCd === '') {
                    $('#disease_cd_edit').addClass('is-invalid');
                    $('.invalid-feedback').filter('.disease-feedback').show();
                    isValid = false;
                }

                if (languageCd === '') {
                    $('#language_cd').addClass('is-invalid');
                    $('.invalid-feedback').filter('.language-feedback').show();
                    isValid = false;
                }

                if (isValid) {
                    form.off('submit').submit();
                }
            });

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
                            var rowToDelete = $('#tblCropDesease tbody tr[data-id="' +
                                response.id + '"]');
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
