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
                </div>
                <div class="mt-3 mb-2">
                    <label class="col-form-label">Description</label>
                    <textarea name="description" class="form-control" placeholder="Enter Description">{{isset($item) ? $item->itemIn->description : null}}</textarea>
                </div>
                <div class="mt-3 mb-2">
                    <label class="col-form-label">Image</label>
                    @if (isset($item) && $item->image)
                        <div class="mt-3">
                            <h6>Existing Images:</h6>
                            <div class="row">
                                @foreach (json_decode($item->image) as $image)
                                    <div class="col-md-2">
                                        <img src="{{ asset('images/item/' . $image) }}" alt="Image" class="img-thumbnail mb-2">
                                        <button type="button" class="btn btn-danger btn-sm delete-image" data-image="{{ $image }}">Remove</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <form>
                    <input name="image[]" id="image-uploadify" type="file" accept="image/*" multiple>
					</form>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-dnd float-end" id="submitBtn">Save<i class="bx bx-save"></i> </button>
                    @if(isset($item))
                        <a href="{{route('employe.item.index')}}" class="btn btn-warning float-end me-2"><i class="bx bx-undo"></i>Back</a>
                    @else
                        <a href="{{route('employe.item.index')}}" class="btn btn-warning float-end me-2"><i class="bx bx-list-ul"></i>List Item</a>
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
                $(this).prop('disabled', true).text('Loading...');
                $('#myForm').submit();
            });

            // Handle image removal with SweetAlert2 confirmation
            $(document).on('click', '.delete-image', function() {
                let image = $(this).data('image');
                let row = $(this).closest('.col-md-2');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('employe.item.deleteImage') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                image: image
                            },
                            success: function(response) {
                                if (response.success) {
                                    row.remove();
                                    Swal.fire('Deleted!', 'Your image has been deleted.', 'success');
                                } else {
                                    Swal.fire('Failed!', 'Failed to remove image.', 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error!', 'An error occurred while processing your request.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
