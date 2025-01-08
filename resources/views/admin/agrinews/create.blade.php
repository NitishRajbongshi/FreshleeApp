@extends('admin.common.layout')

@section('title', 'AgriNewsManager')

@section('custom_header')
@endsection

@section('main')

<div class="card">
    <div class="d-flex align-items-center">
        <h5 class="card-header">Create New Agri News</h5>
    </div>

    <div class="card-body">
        <form action="{{route('admin.agrinews.create')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="name">Title</label>
            <input type="text" class="form-control @error('newsTitle') is-invalid @enderror" required id="newsTitle" name="newsTitle" placeholder="News Title">

            @error('newsTitle')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="category_cd">Select Category</label>
            <select class="form-select  @error('category_cd') is-invalid @enderror"
            id="category_cd" name="category_cd" aria-label="Select News Category">
            <option selected disabled value="">Select News Category</option>
            @forelse ($newsCategories as $item)
            <option value="{{$item->catg_cd}}">{{$item->catg_descr}}</option>
            @empty
                <option disabled value="">No data found</option>
            @endforelse
            </select>

            @error('category_cd')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="phone">News in Details</label>
            {{-- <input type="text" id="phone" name="phone"
            class="form-control phone-mask  @error('phone') is-invalid @enderror"> --}}
            <textarea name="newsDescription" id="newsDescription" class="form-control @error('newsDescription') is-invalid @enderror" rows="10"></textarea>
            @error('newsDescription')
                <div class="invalid-feedback">
                {{ $message }}
                </div>
            @enderror
        </div>

        {{-- File attach1--}}
        <div class="mb-3">
            <label for="newsFile1" class="form-label">Attach Files [1]</label>
            <div class="input-group input-group-merge">
                <input type="file" class="form-control" name="newsFile1" id="newsFile1">
            </div>
        </div>

        <div class="mb-3">
            <label for="newsFile2" class="form-label">Attach Files [2]</label>
            <div class="input-group input-group-merge">
                <input type="file" class="form-control" name="newsFile2" id="newsFile2">
            </div>
        </div>

        <div class="mb-3">
            <label for="newsFile3" class="form-label">Attach Files [3]</label>
            <div class="input-group input-group-merge">
                <input type="file" class="form-control" name="newsFile3" id="newsFile3">
            </div>
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label" for="email">News Creator
            <span class="badge bg-label-warning">Read Only</span></label>
            <div class="input-group input-group-merge">
            <input type="text" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ auth()->user()->user_id }}" readonly placeholder="john.doe" aria-label="john.doe" aria-describedby="basic-default-email2">

            @error('email')
                <div class="invalid-feedback">
                {{$message}}
                </div>
            @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

</div>


@endsection {{-- End of main --}}

@section('custom_js')
<script>
 $(document).ready( function () {

const allElements = document.querySelectorAll('*');
        allElements.forEach(el => {
            el.style.fontSize = '14px';
        });

    });

</script>
@endsection
