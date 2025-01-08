@extends('admin.common.layout')

@section('title', 'Create Item')

@section('custom_header')
@endsection

@section('main')
    @if ($message = Session::get('error'))
        <div id="errorAlert" class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Add Market Item</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.freshlee.master.item.create') }}" method="POST" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="mb-3 col-sm-12 col-md-6">
                        <label class="form-label" for="item_name">Item Name</label>
                        <input type="text" class="form-control @error('item_name') is-invalid @enderror" id="item_name"
                            name="item_name" placeholder="Item Name" value="{{ old('item_name') }}">
                        @error('item_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3 col-sm-12 col-md-6">
                        <label class="form-label" for="perishability_cd">Perishability Type</label>
                        <select class="form-select @error('perishability_cd') is-invalid @enderror" id="perishability_cd"
                            name="perishability_cd">
                            <option value="">Select Perishability Type</option>
                            @foreach ($perishabilityType as $id => $desc)
                                <option value="{{ $id }}" {{ old('perishability_cd') == $id ? 'selected' : '' }}>
                                    {{ $desc }}
                                </option>
                            @endforeach
                        </select>
                        @error('perishability_cd')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3 col-sm-12 col-md-6">
                        <label class="form-label" for="item_category_cd">Item Category</label>
                        <select class="form-select @error('item_category_cd') is-invalid @enderror" id="item_category_cd"
                            name="item_category_cd">
                            <option value="">Select Item Category</option>
                            @foreach ($itemCategories as $id => $desc)
                                <option value="{{ $id }}" {{ old('item_category_cd') == $id ? 'selected' : '' }}>
                                    {{ $desc }}
                                </option>
                            @endforeach
                        </select>
                        @error('item_category_cd')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3 col-sm-12 col-md-6">
                        <label class="form-label" for="product_type_cd">Product Type</label>
                        <select class="form-select @error('product_type_cd') is-invalid @enderror" id="product_type_cd"
                            name="product_type_cd">
                            <option value="">Select Product Type</option>
                            @foreach ($productTypes as $id => $desc)
                                <option value="{{ $id }}" {{ old('product_type_cd') == $id ? 'selected' : '' }}>
                                    {{ $desc }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_type_cd')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3 col-sm-12 col-md-6">
                        <label class="form-label" for="farm_life_in_days">Farm Life (days)</label>
                        <input type="number" class="form-control @error('farm_life_in_days') is-invalid @enderror"
                            id="farm_life_in_days" name="farm_life_in_days" placeholder="Farm Life"
                            value="{{ old('farm_life_in_days') }}">
                        @error('farm_life_in_days')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3 col-sm-12 col-md-6">
                        <label class="form-label" for="min_qty_to_order">Min Order Quantity</label>
                        <input type="number" class="form-control @error('min_qty_to_order') is-invalid @enderror"
                            id="min_qty_to_order" name="min_qty_to_order" placeholder="Min Order Quantity"
                            value="{{ old('min_qty_to_order') }}">
                        @error('min_qty_to_order')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3 col-sm-12 col-md-6">
                        <label class="form-label" for="unit_min_order_qty">Minimum Order Unit</label>
                        <select class="form-select @error('unit_min_order_qty') is-invalid @enderror"
                            id="unit_min_order_qty" name="unit_min_order_qty">
                            <option value="">Select Item Unit</option>
                            <option value="gm" {{ old('unit_min_order_qty') == $id ? 'selected' : '' }}>GM</option>
                            <option value="kg" {{ old('unit_min_order_qty') == $id ? 'selected' : '' }}>KG</option>
                            <option value="litre" {{ old('unit_min_order_qty') == $id ? 'selected' : '' }}>Litre</option>
                            <option value="mutha" {{ old('unit_min_order_qty') == $id ? 'selected' : '' }}>Mutha</option>
                            <option value="unit" {{ old('unit_min_order_qty') == $id ? 'selected' : '' }}>Unit</option>
                        </select>
                        @error('unit_min_order_qty')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3 col-sm-12 col-md-6">
                        <label class="form-label" for="item_image">Item Image (jpg/ jpeg/ png)</label>
                        <input type="file" class="form-control @error('item_image') is-invalid @enderror" id="item_image"
                            name="item_image" value="{{ old('item_image') }}">
                        @error('item_image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('admin.freshlee.master.item') }}" class="btn btn-warning">Cancel</a>
            </form>
        </div>
    </div>
@endsection
@section('custom_js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var errorAlert = document.getElementById('errorAlert');
        if (errorAlert) {
            setTimeout(function() {
                errorAlert.style.opacity = '0';
                errorAlert.style.transition = 'opacity 0.5s ease-out';
                setTimeout(function() {
                    errorAlert.remove();
                }, 500);
            }, 5000);
        }
    });
</script>
@endsection
