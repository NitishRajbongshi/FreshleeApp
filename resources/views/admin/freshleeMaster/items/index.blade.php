@extends('admin.common.layout')

@section('title', 'Crop Name Management')

@section('custom_header')
@endsection

@section('main')
    @if ($message = Session::get('success'))
        <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div id="successAlert" class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">Freshlee Market Item Information Management</h5>
            <div>
                <a href="{{ route('admin.freshlee.master.item.create') }}" class="btn btn-outline-success">
                    <i class="tf-icons bx bx-plus-medical"></i>Add Item
                </a>
            </div>
        </div>

        <div class="table-responsive text-nowrap px-4">
            <table class="table" id="tblUser" style="font-size: 0.8rem">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Item Name</th>
                        <th>Perishability</th>
                        <th>Category</th>
                        <th>Item Type</th>
                        <th>Farm Life</th>
                        <th>Min. Order</th>
                        <th>Item Unit</th>
                        <th>Item Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($marketItems as $index => $item)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->item_name }}</>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->perishability_descr }}
                            </td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->item_category_desc }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->product_name }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;text-align: center;">
                                {{ $item->farm_life_in_days }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;text-align: center;">
                                {{ $item->min_qty_to_order }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;text-align: center;">
                                {{ $item->unit_min_order_qty }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;text-align: center;">
                                <a href="#" class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal"
                                    data-bs-target="#imageModal" data-id="{{ $item->item_cd }}">
                                    <i class="tf-icons bx bx-show"></i> View
                                </a>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal"
                                    data-bs-target="#editModal" data-id="{{ $item->item_cd }}"
                                    data-item-name="{{ $item->item_name }}"
                                    data-item-image="{{ $item->item_image_file_path }}"
                                    data-perishability-cd="{{ $item->perishability_cd }}"
                                    data-item-category="{{ $item->item_category_cd }}"
                                    data-product-type="{{ $item->product_type_cd }}"
                                    data-farm-life="{{ $item->farm_life_in_days }}"
                                    data-min-order="{{ $item->min_qty_to_order }}"
                                    data-item-unit="{{ $item->unit_min_order_qty }}">
                                    <i class="tf-icons bx bx-edit"></i> Edit
                                </a>
                                {{-- <a href="#" class="btn btn-sm btn-outline-danger delete-btn" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-item_cd="{{ $item->item_cd }}">
                                    <i class="tf-icons bx bx-trash"></i>
                                </a> --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel"><i class='bx bxs-image-add me-2'></i>Item Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="imageContainer w-full h-auto">
                        {{-- Image will be loaded via AJAX --}}
                    </div>
                    <div class="figcaption my-2" style="font-size: 0.8rem; text-align: center;">
                        {{-- Item name will be loaded via AJAX --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel"><i class='bx bxs-message-square-edit me-2'></i>Edit Item Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST" action="{{ route('admin.freshlee.master.item.update') }}"
                    autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body d-flex flex-wrap">
                        <input type="hidden" name="item_cd" id="itemCd">
                        <div class="mb-3 col-sm-12 col-md-6 px-2">
                            <label for="item_name" class="form-label">Item Name</label>
                            <input type="text" class="form-control" id="item_name" name="item_name"
                                placeholder=" Enter Item Name" required>
                            <div class="invalid-feedback item-name-feedback" style="display: none;">
                                Please provide a Item Name.
                            </div>
                        </div>
                        <div class="mb-3 col-sm-12 col-md-6 px-2">
                            <label for="perishability_cd" class="form-label">Perishability</label>
                            <select class="form-select" id="perishability_cd" name="perishability_cd" required>
                                <option value="">Select Perishability</option>
                                @foreach ($perishabilityTypes as $id => $desc)
                                    <option value="{{ $id }}">
                                        {{ $desc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-sm-12 col-md-6 px-2">
                            <label for="item_category_cd" class="form-label">Item Category</label>
                            <select class="form-select" id="item_category_cd" name="item_category_cd" required>
                                <option value="">Select Item Category</option>
                                @foreach ($itemCategories as $id => $desc)
                                    <option value="{{ $id }}">
                                        {{ $desc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-sm-12 col-md-6 px-2">
                            <label for="product_type_cd" class="form-label">Product Type</label>
                            <select class="form-select" id="product_type_cd" name="product_type_cd" required>
                                <option value="">Select Product Type</option>
                                @foreach ($productTypes as $id => $desc)
                                    <option value="{{ $id }}">
                                        {{ $desc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-sm-12 col-md-6 px-2">
                            <label for="farm_life" class="form-label">Farm Life</label>
                            <input type="number" class="form-control" id="farm_life" name="farm_life"
                                placeholder="Enter Farm Life" required>
                        </div>
                        <div class="mb-3 col-sm-12 col-md-6 px-2">
                            <label for="min_order" class="form-label">Min. Order</label>
                            <input type="number" class="form-control" id="min_order" name="min_order"
                                placeholder="Enter Min. Order" required>
                        </div>
                        <div class="mb-3 col-sm-12 col-md-6 px-2">
                            <label for="item_unit" class="form-label">Minimum Order Unit</label>
                            <select class="form-select" id="item_unit" name="item_unit" required>
                                <option value="">Select Item Unit</option>
                                <option value="gm">GM</option>
                                <option value="kg">KG</option>
                                <option value="litre">Litre</option>
                                <option value="mutha">Mutha</option>
                                <option value="unit">Unit</option>
                            </select>
                        </div>
                        <div class="mb-3 col-sm-12 col-md-6 px-2">
                            <label for="item_image" class="form-label">Item Image</label>
                            <input type="file" class="form-control" id="item_image" name="item_image"
                                accept="image/*">
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
                    <h5 class="modal-title" id="deleteModalLabel">Delete Crop Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteForm" method="POST" action="{{ route('admin.appmaster.crops.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Are you sure you want to delete this crop information?</p>
                        <input type="hidden" name="crop_name_cd" id="deleteId">
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
        document.addEventListener('DOMContentLoaded', function() {
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

            $('#imageModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var itemCd = button.data('id');
                var modal = $(this);
                var modalBody = modal.find('.modal-body');
                var imageContainer = modalBody.find('.imageContainer');
                var figcaption = modalBody.find('.figcaption');

                $.ajax({
                    url: "{{ route('admin.freshlee.master.item.image') }}",
                    method: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}',
                        item_cd: itemCd
                    },
                    success: function(response) {
                        figcaption.html('<p>Fig: ' + response.item_name + '</p>');
                        imageContainer.html('<img src="' + response.image_src +
                            '" class="img-fluid" alt="Item Image" style="min-width: 100%; max-height: 15rem; border-radius: 7px;">'
                            );
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr);
                    }
                });
            });

            $('#editModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var itemCD = button.data('id');
                var itemName = button.data('item-name');
                var itemImage = button.data('item-image');
                var perishabilityCD = button.data('perishability-cd');
                var itemCategory = button.data('item-category');
                var productType = button.data('product-type');
                var farmLife = button.data('farm-life');
                var minOrder = button.data('min-order');
                var itemUnit = button.data('item-unit');

                var modal = $(this);
                modal.find('#itemCd').val(itemCD);
                modal.find('#item_name').val(itemName);
                modal.find('#perishability_cd').val(perishabilityCD);
                modal.find('#item_category_cd').val(itemCategory);
                modal.find('#product_type_cd').val(productType);
                modal.find('#farm_life').val(farmLife);
                modal.find('#min_order').val(minOrder);
                modal.find('#item_unit').val(itemUnit);
            });

            $('#deleteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var crop_name_cd = button.data('crop_name_cd');

                var modal = $(this);
                modal.find('#deleteId').val(crop_name_cd);
            });

            // Handle form submission for deletion
            $('#deleteForm').on('submit', function(e) {
                e.preventDefault();

                var form = $(this);

                $.ajax({
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        form.off('submit').submit();
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr);
                    }
                });
            });
        });
    </script>
@endsection
