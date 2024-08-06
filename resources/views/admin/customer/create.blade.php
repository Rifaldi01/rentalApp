@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="row">
            <div class="col-sm-6 mt-3">
                @if(isset($customer))
                    <h5 class="mb-4 ms-3">Edit Customer<i class="bx bx-edit"></i></h5>
                @else
                    <h5 class="mb-4 ms-3">Register Customer<i class="bx bx-user-plus"></i></h5>
                @endif
            </div>
            <div class="col-sm-6 mt-3">
                <a href="{{route('admin.customer.index')}}" class="btn btn-warning float-end me-3">List Customer</a>
            </div>
        </div>
        
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert border-0 border-start border-5 border-danger alert-dismissible fade show py-2">
                    <div class="d-flex align-items-center">
                        <div class="font-35 text-danger"><i class='bx bxs-message-square-x'></i></div>
                        <div class="ms-3">
                            <h6 class="mb-0 text-danger">Error</h6>
                            <div>{{ $error }}</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endforeach
        @endif
        
        <form class="card-body p-4" action="{{$url}}" method="POST" enctype="multipart/form-data" id="myForm">
            @csrf
            @isset($customer)
                @method('PUT')
            @endif
            
            <div class="row mb-3">
                <label for="input42" class="col-sm-3 col-form-label"><i class="text-danger">*</i> Customer Name</label>
                <div class="col-sm-9">
                    <div class="position-relative input-icon">
                        <input type="text" name="name" class="form-control" id="input42" placeholder="Enter Customer Name" value="{{ isset($customer) ? $customer->name : '' }}">
                        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-user'></i></span>
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="input43" class="col-sm-3 col-form-label"><i class="text-danger">*</i> Phone Whatsapp</label>
                <div class="col-sm-9">
                    <div class="position-relative input-icon">
                        <input type="number" name="phone" class="form-control" id="input43" placeholder="81XXXXXXXXXX" value="{{ isset($customer) ? $customer->phone : '' }}">
                        <span class="position-absolute top-50 translate-middle-y"><i class='bx bxl-whatsapp'></i></span>
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="input43" class="col-sm-3 col-form-label">Phone</label>
                <div class="col-sm-9">
                    <div class="position-relative input-icon">
                        <input type="number" name="phn" class="form-control" id="input43" placeholder="(optional)" value="{{ isset($customer) ? $customer->phn : '' }}">
                        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-phone'></i></span>
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="input44" class="col-sm-3 col-form-label"><i class="text-danger">*</i> No Identity</label>
                <div class="col-sm-9">
                    <div class="position-relative input-icon">
                        <input type="number" name="no_identity" class="form-control" id="input44" placeholder="32XXXXXXXXXXXXX" value="{{ isset($customer) ? $customer->no_identity : '' }}">
                        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-credit-card-front'></i></span>
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="input47" class="col-sm-3 col-form-label"><i class="text-danger">*</i> Address</label>
                <div class="col-sm-9">
                    <textarea class="form-control" name="addres" id="input47" rows="3" placeholder="Address">{{ isset($customer) ? $customer->addres : '' }}</textarea>
                </div>
            </div>
            
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Identity</label>
                <div class="col-sm-9">
                    <input name="image[]" class="form-control" type="file" accept="image/*" multiple>

                    @if (isset($customer) && $customer->image)
                        <div class="mt-3">
                            <h6>Existing Images:</h6>
                            <div class="row">
                                @foreach (json_decode($customer->image) as $image)
                                    <div class="col-md-2">
                                        <img src="{{ asset('images/identity/' . $image) }}" alt="Image" class="img-thumbnail mb-2">
                                        <button type="button" class="btn btn-danger btn-sm delete-image" data-image="{{ $image }}">Remove</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="d-md-flex d-grid align-items-center gap-3 float-end">
                <button type="submit" class="btn btn-dnd px-4" id="submitBtn">Save <i class="bx bx-save me-0"></i></button>
            </div>
        </form>
    </div>
@endsection

@push('head')
    <link rel="stylesheet" type="text/css" href="{{ URL::to('assets/css/coba1.css') }}">
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
                            url: '{{ route('admin.customer.deleteImage') }}',
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
