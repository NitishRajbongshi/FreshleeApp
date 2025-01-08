@extends('admin.common.layout')

@section('title', 'Review Crop Image')

@section('custom_header')
@endsection

@section('main')

    @if ($message = Session::get('success'))
        <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">Review Crop Image</h5>
        </div>

        <div class="table-responsive text-nowrap px-4">
            <table class="table" id="tblUser">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Uploaded by</th>
                        <th>Disease Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($data as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->name }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->disease_name }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal"
                                    data-bs-target="#editModal" data-disease-name="{{ $item->disease_name }}"
                                    data-user-uploaded-image-path="{{ $item->user_uploaded_image_path }}"
                                    data-user-selected-image-path="{{ $item->user_selected_image_path }}">
                                    <i class="tf-icons bx bx-edit"></i> Edit
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
    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Review Images</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4" id="images">
                        <div class="col text-center">
                            <h6>Uploaded Image</h6>
                            <img id="userUploadedImage" src="" alt="User Uploaded" class="img-fluid"
                                style="max-height: 300px; border: 1px solid #dee2e6; border-radius: 5px;" />
                        </div>
                        <div class="col text-center">
                            <h6>Original Image</h6>
                            <img id="userSelectedImage" src="" alt="User Selected" class="img-fluid"
                                style="max-height: 300px; border: 1px solid #dee2e6; border-radius: 5px;" />
                        </div>
                    </div>

                    <div class="text-center mb-4" id="review">
                        <h6>Review Status</h6>
                        <p>Select the appropriate status for the images above:</p>
                    </div>

                    <div id="statusButtons">
                        <div class="row text-center">
                            <div class="col">
                                <button class="btn btn-success mx-2" id="btnCorrect">
                                    <i class="tf-icons bx bx-check"></i> Correct
                                </button>
                                <p>Original and uploaded image match with good quality.</p>
                            </div>
                            <div class="col">
                                <button class="btn btn-warning mx-2" id="btnNotCorrect">
                                    <i class="tf-icons bx bx-x"></i> Not Correct
                                </button>
                                <p>Good quality, but the uploaded image does not match the original.</p>
                            </div>
                            <div class="col">
                                <button class="btn btn-danger mx-2" id="btnReject">
                                    <i class="tf-icons bx bx-trash"></i> Reject
                                </button>
                                <p>The image quality is low and not acceptable.</p>
                            </div>
                        </div>
                    </div>

                    <div class="collapse" id="additionalInfo">
                        <div class="mt-4">
                            <h6>Select Crop Type and Disease:</h6>
                            <select id="cropType" class="form-select mb-2">
                                <option value="">Select Crop Type</option>
                                @foreach ($cropTypes as $code => $desc)
                                    <option value="{{ $code }}">{{ $desc }}</option>
                                @endforeach
                            </select>
                            <select id="diseaseName" class="form-select mb-2" disabled>
                                <option value="">Select Disease</option>
                            </select>
                            <button class="btn btn-primary" id="btnSave" disabled>Save</button>
                            <button class="btn btn-secondary" id="btnCancelNotCorrect">Cancel</button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('custom_js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

        const allElements = document.querySelectorAll('*');
                    allElements.forEach(el => {
                        el.style.fontSize = '14px';
                    });
        $('#tblDesignation').DataTable();


            var successAlert = document.getElementById('successAlert');

            if (successAlert) {
                setTimeout(function() {
                    successAlert.style.opacity = '0';
                    successAlert.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(function() {
                        successAlert.remove();
                    }, 500);
                }, 5000);
            }
        });

        $(document).ready(function() {
            $('#tblUser').DataTable();

            const diseasesByCropType = @json($diseases);

            $('.edit-btn').on('click', function() {
                const diseaseName = $(this).data('disease-name');
                const userUploadedImagePath = $(this).data('user-uploaded-image-path');
                const userSelectedImagePath = $(this).data('user-selected-image-path');


                $('#userUploadedImage').attr('src', userUploadedImagePath);
                $('#userSelectedImage').attr('src', userSelectedImagePath);
                $('#editModalLabel').text(`Review Images for ${diseaseName}`);
                resetModal(); // Reset modal state
            });

            $('#btnNotCorrect').on('click', function() {
                $('#images').hide();
                $('#review').hide();
                $('#statusButtons button').hide(); // Hide all action buttons
                $('#statusButtons').find('p').hide(); // Hide descriptions
                $('#additionalInfo').collapse('show'); // Show additional info

            });

            $('#btnCancelNotCorrect').on('click', function() {
                resetModal(); // Reset modal on cancel
            });


            $('#btnCorrect').on('click', function() {
                const modalTitle = $('#editModalLabel').text();
                const diseaseName = modalTitle.substring(modalTitle.indexOf('for') + 4)
                    .trim(); // Adjust the index based on the space

                const userUploadedImagePath = $('#userUploadedImage').attr('src');

                // Create a hidden form to submit the data
                const form = $('<form>', {
                    action: '{{ route('save.correct.image') }}',
                    method: 'POST'
                });

                form.append($('<input>', {
                    type: 'hidden',
                    name: 'disease_name',
                    value: diseaseName
                }));

                form.append($('<input>', {
                    type: 'hidden',
                    name: 'user_uploaded_image_path',
                    value: userUploadedImagePath
                }));

                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: '{{ csrf_token() }}'
                }));

                // Append the form to the body and submit it
                $('body').append(form);
                form.submit();
            });

            $('#btnReject').on('click', function() {
                resetModal(); // Reset modal on any button click
            });

            $('#cropType').change(function() {
                const cropTypeCd = $(this).val();
                $('#diseaseName').prop('disabled', !cropTypeCd);
                $('#diseaseName').empty().append('<option value="">Select Disease</option>');

                if (diseasesByCropType[cropTypeCd]) {
                    $.each(diseasesByCropType[cropTypeCd], function(index, disease) {
                        $('#diseaseName').append('<option value="' + index + '">' + disease +
                            '</option>');
                    });
                }
            });

            $('#diseaseName').change(function() {
                const cropType = $('#cropType').val();
                const diseaseSelected = $(this).val();

                $('#btnSave').prop('disabled', !$(this).val());

                if (cropType && diseaseSelected) {
                    $('#userSelectedImage').attr('src',
                        'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQmNDSN1D6z0V7Sek8thwiF0g83sa8JAOi3Mw&s'
                    );
                    $('#images').show(); // Show the images section
                } else {
                    $('#images').hide(); // Optionally hide the images section if not selected
                }

            });

            function resetModal() {
                // $('#userSelectedImage').attr('src', userSelectedImagePath);
                $('#images').show();
                $('#statusButtons button').show(); // Show all action buttons
                $('#statusButtons').find('p').show(); // Show descriptions
                $('#additionalInfo').collapse('hide'); // Hide additional info
                $('#review').show();
                $('#cropType').val('').change(); // Reset crop type
                $('#diseaseName').val('').prop('disabled', true).empty().append(
                    '<option value="">Select Disease</option>');
                $('#btnSave').prop('disabled', true); // Disable save button
            }
        });
    </script>

@endsection
