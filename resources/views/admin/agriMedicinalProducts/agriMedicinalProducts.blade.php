@extends('admin.common.layout')

@section('title', 'All Crop Variety Management')

@section('main')
    <div id="successAlert" class="alert alert-success alert-dismissible fade d-none" role="alert">
        <span class="alert-message"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">Agri Medicinal Products Details</h5>
            <div>
                <a href="{{ route('admin.agriMedicinalProducts.createmedicinalproducts') }}" class="btn btn-outline-success">
                    <i class="tf-icons bx bx-plus-medical"></i>Add New Medicinal Products Details
                </a>
            </div>
        </div>

        <div class="px-4 py-2">
            <div class="mb-3">
                <label for="product_type_cd" class="form-label">Select Product Type</label>
                <select class="form-select" id="product_type_cd">
                    <option value="">Select Product Type</option>
                    @foreach ($productTypes as $code => $description)
                        <option value="{{ $code }}">{{ $description }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="status_code" class="form-label">Select Product Status</label>
                <select class="form-select" id="status_code">
                    <option value="">Select Product Status</option>
                </select>
            </div>

        </div>

        <div class="table-responsive text-nowrap px-4">
            <table class="table" id="tblUser" style="display:none;">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Technical Code</th>
                        <th>Technical Name</th>
                        <th>Trade Code</th>
                        <th>Trade Name</th>
                        <th>Manufacturer Name</th>
                        <th>Is Registered </th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0" id="tableBody">
                </tbody>
            </table>
        </div>


        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Medicinal Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="deleteForm" method="POST" action="{{ route('admin.agriMedicinalProducts.agriMedicinalProducts.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <p>Are you sure you want to delete this medicinal product?</p>
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

        <div class="modal fade" id="imageModal" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="d-flex justify-content-center">
                            <img id="modalImage" src="" alt="Image 1" class="img-fluid mx-2"
                                style="max-width: 30%; max-height: 80vh; object-fit: contain;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Medicinal Products</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editForm" action="{{ route('admin.agriMedicinalProducts.update') }}"
                    enctype="multipart/form-data"  method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="col mb-3">
                                <label for="product_type_id" class="form-label">Select Product Type</label>
                                <select class="form-select @error('product_type_id') is-invalid @enderror" id="product_type_id"
                                    name="product_type_cd">
                                    <option value="">Select Product Type</option>
                                    @foreach ($productTypes as $id => $desc)
                                        <option value="{{ $id }}">{{ $desc }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback product-type-feedback" style="display: none;">
                                    Please select a Product Type.
                                </div>
                            </div>

                            <div class="col mb-3">
                                <label for="status_code_id" class="form-label">Select Product Status</label>
                                <select class="form-select @error('status_code_id') is-invalid @enderror" id="status_code_id"
                                    name="status">
                                    <option value="">Select Product Status</option>
                                    @foreach ($productStatus as $id => $desc)
                                        <option value="{{ $id }}">{{ $desc }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback status-feedback" style="display: none;">
                                    Please select a Product Status.
                                </div>
                            </div>

                            <div class="form-group" id="statusReasonContainer" style="display:none;">
                                <label for="statusReason">Reason for changing product status</label>
                                <textarea class="form-control" id="statusReason" name="statusReason" rows="3"></textarea>
                                <div class="invalid-feedback status-reason-feedback" style="display: none;">
                                    Please provide a reason for changing the product status.
                                </div>
                            </div>

                            <input type="hidden" name="id" id="idName">

                            <div class="mb-3">
                                <label class="form-label">Is Registered?</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_registered" id="is_registered_yes" value="Y">
                                    <label class="form-check-label" for="is_registered_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_registered" id="is_registered_no" value="N">
                                    <label class="form-check-label" for="is_registered_no">No</label>
                                </div>
                            </div>

                            <div class="form-group" id="isRegisteredReasonContainer" style="display:none;">
                                <label for="isRegisteredReason">Reason for changing is registered</label>
                                <textarea class="form-control" id="isRegisteredReason" name="isRegisteredReason" rows="3"></textarea>
                                <div class="invalid-feedback is-registered-reason-feedback" style="display: none;">
                                    Please provide a reason for changing the registered status.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="techCode" class="form-label">Technical Code</label>
                                <input type="text" class="form-control" id="techCode" name="technical_code"
                                    placeholder="Enter Technical Code">
                                <div class="invalid-feedback techCode-feedback" style="display: none;">
                                    Please provide Technical Code
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="techName" class="form-label">Technical Name</label>
                                <input type="text" class="form-control" id="techName" name="technical_name"
                                    placeholder="Enter Technical Name">
                                <div class="invalid-feedback techName-feedback" style="display: none;">
                                    Please provide Technical Name
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="tradeCode" class="form-label">Trade Code</label>
                                <input type="text" class="form-control" id="tradeCode" name="trade_code"
                                    placeholder="Enter Trade Code">
                                <div class="invalid-feedback tradeCode-feedback" style="display: none;">
                                    Please provide Trade Code
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="tradeName" class="form-label">Trade Name</label>
                                <input type="text" class="form-control" id="tradeName" name="trade_name"
                                    placeholder="Enter Trade Name">
                                <div class="invalid-feedback tradeName-feedback" style="display: none;">
                                    Please provide Trade Name
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="manufacturerName" class="form-label">Manufacturer Name</label>
                                <input type="text" class="form-control" id="manufacturerName" name="manufacturer_name"
                                    placeholder="Enter Manufacturer Name">
                                <div class="invalid-feedback manufacturerName-feedback" style="display: none;">
                                    Please provide Manufacturer Name
                                </div>
                            </div>


                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                <img id="image1" class="img-thumbnail mb-2" alt="Image preview"
                                    style="width: 100px; height: 100px;">
                                <input type="file"
                                    class="form-control @error('image_file_path') is-invalid @enderror" id="image_file_path"
                                    name="image_file_path" accept="image/png, image/jpeg, image/jpg" onchange="previewImage()">
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
    </div>
@endsection

@section('custom_js')
    <script>
        function previewImage() {
            const fileInput = $(`#image_file_path`)[0];
            const imageContainer = $(`#image1`);

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imageContainer.attr('src', e.target.result);
                };
                reader.readAsDataURL(fileInput.files[0]);
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const allElements = document.querySelectorAll('*');
            allElements.forEach(el => {
                el.style.fontSize = '14px';
            });

            const tblUser = document.getElementById('tblUser');
            const tableBody = document.getElementById('tableBody');

            var cropmeds = @json($cropmeds);


            $('#product_type_cd').on('change', function() {
                var productTypeCd = $(this).val();
                var productStatusDropdown = $('#status_code');
                productStatusDropdown.empty();
                productStatusDropdown.append('<option value="">Select Product Status</option>');

                tblUser.style.display = 'none';
                tableBody.innerHTML = '';


                if (productTypeCd) {
                    @foreach ($productStatus as $statusCode => $statusDescr)
                        productStatusDropdown.append('<option value="{{ $statusCode }}">{{ $statusDescr }}</option>');
                    @endforeach
                }

            });

            $('#status_code').on('change', function() {
                var selectedProductTypeCd = $('#product_type_cd').val();
                var selectedStatusCode = $(this).val();
                tblUser.style.display = 'none';
                tableBody.innerHTML = '';

                if (selectedProductTypeCd && selectedStatusCode) {
                    var filteredMeds = cropmeds.filter(function(item) {
                        return item.product_type_cd == selectedProductTypeCd && item.status == selectedStatusCode;
                    });

                    // Populate the table with filtered data
                    if (filteredMeds.length > 0) {
                        filteredMeds.forEach((item, index) => {
                            const row = `
                                <tr data-id="${item.id}" data-original-index="${index + 1}">
                                    <td>${index + 1}</td>
                                    <td style="overflow-wrap: break-word; white-space: normal;">${item.technical_code}</td>
                                    <td style="overflow-wrap: break-word; white-space: normal;">${item.technical_name}</td>
                                    <td style="overflow-wrap: break-word; white-space: normal;">${item.trade_code}</td>
                                    <td style="overflow-wrap: break-word; white-space: normal;">${item.trade_name}</td>
                                    <td style="overflow-wrap: break-word; white-space: normal;">${item.manufacturer_name}</td>
                                    <td style="overflow-wrap: break-word; white-space: normal;">${item.is_registered === 'Y' ? 'Yes' : 'No'}</td>
                                    <td><a href="#" class="text-primary font-weight-bold text-decoration-underline view-images-link" data-item="${item.image_file_path || ''}}">View Images</a></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id="${item.id}"
                                        data-product-type-cd="${item.product_type_cd}"
                                        data-technical-code="${item.technical_code}"
                                        data-technical-name="${item.technical_name}"
                                        data-trade-code="${item.trade_code}"
                                        data-trade-name="${item.trade_name}"
                                        data-manufacturer-name="${item.manufacturer_name}"
                                        data-status="${item.status}"
                                        data-image-file="${item. image_file_path}"
                                        data-registered="${item.is_registered}">
                                        <i class="tf-icons bx bx-edit"></i> Edit
                                        </a>
                                        <a href="#" class="btn btn-sm btn-outline-danger delete-btn" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="${item.id}">
                                            <i class="tf-icons bx bx-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            `;
                            tableBody.insertAdjacentHTML('beforeend', row);
                        });
                    }

                    tblUser.style.display = 'table';
                    if ($.fn.dataTable.isDataTable('#tblUser')) {
                        var table = $('#tblUser').DataTable();
                        table.clear();
                        table.rows.add($(tableBody).find('tr'));
                        table.draw();
                    } else {
                        $('#tblUser').DataTable({
                            paging: true,
                            searching: true,
                            info: true,
                        });
                    }


                    tableBody.addEventListener('click', function(e) {
                        if (e.target && e.target.matches('a.view-images-link')) {
                            try {
                                const item = e.target.getAttribute('data-item');

                                const modalElement = document.getElementById('imageModal');

                                modalElement.addEventListener('hidden.bs.modal',function() {
                                        document.body.style.overflow ='auto';
                                    });

                                const existingModal = bootstrap.Modal.getInstance(modalElement);
                                if (existingModal) {
                                    existingModal.dispose();
                                }

                                const newImageModal = new bootstrap.Modal(modalElement);

                                document.getElementById('modalImage').src = item || '';

                                if (newImageModal) {
                                    newImageModal.show();
                                }
                            } catch (error) {
                                console.error('Error parsing item:', error);
                            }
                        }
                    });
                }
            });


            $('#editModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const id = button.data('id');
                const productTypeCd = button.data('product-type-cd');
                const status = button.data('status');
                const registered = button.data('registered');
                const techCd = button.data('technical-code');
                const techNm = button.data('technical-name');
                const tradeCd = button.data('trade-code');
                const tradeNm = button.data('trade-name');
                const manuName = button.data('manufacturer-name');
                const imagePath = button.data('image-file');

                const modal = $(this);
                modal.find('#idName').val(id);
                modal.find('#product_type_id').val(productTypeCd);
                modal.find('#status_code_id').val(status);
                modal.find('#cropVariety').val(registered);
                modal.find('#techCode').val(techCd);
                modal.find('#techName').val(techNm);
                modal.find('#tradeCode').val(tradeCd);
                modal.find('#tradeName').val(tradeNm);
                modal.find('#manufacturerName').val(manuName);
                modal.find('#image1').attr('src', imagePath);

                modal.find('#image_file_path').off('change').on('change', function() {
                    previewImage();
                });

                $('#statusReasonContainer').hide();
                $('#isRegisteredReasonContainer').hide();


                if (registered === 'Y') {
                    modal.find('#is_registered_yes').prop('checked', true);
                    modal.find('#is_registered_no').prop('checked', false);
                } else if (registered === 'N') {
                    modal.find('#is_registered_yes').prop('checked', false);
                    modal.find('#is_registered_no').prop('checked', true);
                }

                modal.data('initialStatus', status);
                modal.data('initialRegistered', registered);
            });

            $('#status_code_id').on('change', function() {
                const selectedStatus = $(this).val();
                const modal = $('#editModal');

                if (selectedStatus !== modal.data('initialStatus')) {
                    $('#statusReasonContainer').show();
                } else {
                    $('#statusReasonContainer').hide();
                }
            });


            $('input[name="is_registered"]').on('change', function() {
                const selectedRegistered = $(this).val();
                const modal = $('#editModal');

                if (selectedRegistered !== modal.data('initialRegistered')) {
                    $('#isRegisteredReasonContainer').show();
                } else {
                    $('#isRegisteredReasonContainer').hide();
                }
            });

            $('#editForm').on('submit', function(e) {
            e.preventDefault();

            var isValid = true;
            var id = $('#idName').val().trim();
            var productTypeCd = $('#product_type_id').val().trim();
            var statusCode = $('#status_code_id').val().trim();
            var techCd = $('#techCode').val().trim();
            var techNam = $('#techName').val().trim();
            var tradeCd = $('#tradeCode').val().trim();
            var tradeNam = $('#tradeName').val().trim();
            var manuNam = $('#manufacturerName').val().trim();
            var isRegistered = $('input[name="is_registered"]:checked').val();
            var imageFile = $('#image_file_path')[0].files[0];
            var statusReason = $('#statusReason').val().trim();
            var isRegisteredReason = $('#isRegisteredReason').val().trim();



            $('#product_type_id').removeClass('is-invalid');
            $('#status_code_id').removeClass('is-invalid');
            $('#statusReason').removeClass('is-invalid');
            $('#isRegisteredReason').removeClass('is-invalid');
            $('#techCode').removeClass('is-invalid');
            $('#techName').removeClass('is-invalid');
            $('#tradeCode').removeClass('is-invalid');
            $('#tradeName').removeClass('is-invalid');
            $('#manufacturerName').removeClass('is-invalid');
            $('.invalid-feedback').hide();


            if (productTypeCd === '') {
                $('#product_type_id').addClass('is-invalid');
                $('.invalid-feedback.product-type-feedback').show();
                isValid = false;
            }

            if (statusCode === '') {
                $('#status_code_id').addClass('is-invalid');
                $('.invalid-feedback.status-feedback').show();
                isValid = false;
            }

            if (statusReason === '' && $('#statusReasonContainer').is(':visible')) {
                $('#statusReason').addClass('is-invalid');
                $('.invalid-feedback.status-reason-feedback').show();
                isValid = false;
            }

            if (isRegisteredReason === '' && $('#isRegisteredReasonContainer').is(':visible')) {
                $('#isRegisteredReason').addClass('is-invalid');
                $('.invalid-feedback.is-registered-reason-feedback').show();
                isValid = false;
            }

            if (techCd === '') {
                $('#techCode').addClass('is-invalid');
                $('.invalid-feedback.techCode-feedback').show();
                isValid = false;
            }

            if (techNam === '') {
                $('#techName').addClass('is-invalid');
                $('.invalid-feedback.techName-feedback').show();
                isValid = false;
            }

            if (tradeCd === '') {
                $('#tradeCode').addClass('is-invalid');
                $('.invalid-feedback.tradeCode-feedback').show();
                isValid = false;
            }

            if (tradeNam === '') {
                $('#tradeName').addClass('is-invalid');
                $('.invalid-feedback.tradeName-feedback').show();
                isValid = false;
            }

            if (manuNam === '') {
                $('#manufacturerName').addClass('is-invalid');
                $('.invalid-feedback.manufacturerName-feedback').show();
                isValid = false;
            }


            if (isValid) {
                var formData = new FormData(this);
                var actionUrl = $(this).attr('action');
                var dt = $(this).serialize();

                formData.append('id', id);
                formData.append('product_type_cd', productTypeCd);
                formData.append('status', statusCode);
                formData.append('is_registered', isRegistered);
                formData.append('technical_code', techCd);
                formData.append('technical_name', techNam);
                formData.append('trade_code', tradeCd);
                formData.append('trade_name', tradeNam);
                formData.append('manufacturer_name', manuNam);
                
                if (imageFile) {
                    formData.append('image_file_path', imageFile);
                }
                if ($('#statusReasonContainer').is(':visible')) {
                    formData.append('status_reason', $('#statusReason').val().trim());
                }

                if ($('#isRegisteredReasonContainer').is(':visible')) {
                    formData.append('registered_reason', $('#isRegisteredReason').val().trim());
                }

                $.ajax({
                    url: $('#editForm').attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#editModal').modal('hide');
                            if (response.success) {
                                $('#editModal').modal('hide');

                                $('#successAlert .alert-message').text(response.message);

                                $('#successAlert').removeClass('d-none').addClass('show');

                                setTimeout(function() {
                                    $('#successAlert').removeClass('show').addClass(
                                        'd-none');
                                }, 5000);

                                const targetRow = $(
                                    `#tblUser tbody tr[data-id="${response.updatedMedicinalProduct.id}"]`
                                );

                                targetRow.find('td:nth-child(2)').text(response.updatedMedicinalProduct.technical_code);
                                targetRow.find('td:nth-child(3)').text(response.updatedMedicinalProduct.technical_name);
                                targetRow.find('td:nth-child(4)').text(response.updatedMedicinalProduct.trade_code);
                                targetRow.find('td:nth-child(5)').text(response.updatedMedicinalProduct.trade_name);
                                targetRow.find('td:nth-child(6)').text(response.updatedMedicinalProduct.manufacturer_name);
                                targetRow.find('td:nth-child(7)').text(response.updatedMedicinalProduct.is_registered === 'Y' ? 'Yes' : 'No');
                                var imagePath = response.updatedMedicinalProduct.image_file_path || '';
                                targetRow.find('td:nth-child(8) a').attr('data-item', imagePath);


                                const editButton = targetRow.find('.edit-btn');
                                editButton.data('id', response.updatedMedicinalProduct.id);
                                editButton.data('product-type-cd', response.updatedMedicinalProduct.product_type_cd);
                                editButton.data('technical-code', response.updatedMedicinalProduct.technical_code);
                                editButton.data('technical-name', response.updatedMedicinalProduct.technical_name);
                                editButton.data('trade-code', response.updatedMedicinalProduct.trade_code);
                                editButton.data('trade-name', response.updatedMedicinalProduct.trade_name);
                                editButton.data('manufacturer-name', response.updatedMedicinalProduct.manufacturer_name);
                                editButton.data('status', response.updatedMedicinalProduct.status);
                                editButton.data('image-file', response.updatedMedicinalProduct.image_file_path);
                                editButton.data('registered', response.updatedMedicinalProduct.is_registered);

                                targetRow.find('.delete-btn').attr('data-id', response.updatedMedicinalProduct.id);

                                targetRow.data('original-index', response.updatedMedicinalProduct.id);
                                var table = $('#tblUser').DataTable();
                                table.draw(false);
                                updateSerialNumbers(table);

                            } else {
                                alert('Failed to update variety.');
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('Error updating:', xhr.responseText);
                        alert('There was an error updating the variety.');
                        }
                    });
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
                            var rowToDelete = $('#tblUser tbody tr[data-id="' +
                                response.id + '"]');
                            var table = $('#tblUser').DataTable();

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
