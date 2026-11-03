@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-head">
            <div class="mt-3">
                @if(isset($item))
                    <h5 class="mb-4 ms-3">Edit Item<i class="bx bx-edit"></i></h5>
                @else
                    <h5 class="mb-4 ms-3">Add Item<i class="bx bx-user-plus"></i></h5>
                @endif
            </div>
        </div>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert border-0 border-start border-5 border-danger alert-dismissible fade show py-2">
                    <div class="d-flex align-items-center">
                        <div class="font-35 text-danger"><i class='bx bxs-message-square-x'></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 text-danger">Error</h6>
                            <div>
                                <div>{{ $error }}</div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endforeach
        @endif
        <div class="card-body p-4">
            <form action="{{$url}}" method="POST" enctype="multipart/form-data" id="myForm">
                @csrf
                @isset($item)
                    @method('PUT')
                @endisset
                <div class="mb-2">
                    <label class="col-form-label">Name Item</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter Namae Item" value="{{isset($item) ? $item->name : null}}">
                </div>
                <div class="mt-3 mb-2">
                    <label for="single-select-field" class="form-label">Category</label>
                    <select name="cat_id" class="form-select" id="single-select-clear-field" data-placeholder="Choose one thing">
                        @foreach($cat as $category)
                            @if(isset($item))
                                <option value="{{ $category->id }}" {{ $item->cat_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @else
                                <option value=""></option>
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="mt-3 mb-2">
                    <label class="col-form-label">No Seri</label>
                    <input type="text" name="no_seri" class="form-control" placeholder="Enter No Seri" value="{{isset($item) ? $item->no_seri : null}}">
                </div><div class="mt-3 mb-2">
                    <label class="col-form-label">Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-dnd float-end" id="submitBtn">Save<i class="bx bx-save"></i> </button>
                    @if(isset($item))
                        <a href="{{route('superadmin.item.index')}}" class="btn btn-warning float-end me-2"><i class="bx bx-undo"></i>Back</a>
                    @else
                        <a href="{{route('superadmin.item.index')}}" class="btn btn-warning float-end me-2"><i class="bx bx-list-ul"></i>List Item</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection

@push('head')

@endpush
@push('js')
    <script>
        $(document).ready(function() {
            $('#submitBtn').click(function() {
                // Disable button dan ubah teksnya
                $(this).prop('disabled', true).text('Loading...');

                // Kirim form secara manual
                $('#myForm').submit();
            });
        });
    </script>
@endpush
