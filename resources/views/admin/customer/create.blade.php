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
        
        <div id="error-container" style="display: none;">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="error-message">{{ $error }}</div>
                    @endforeach
                @endif
            </div>
        
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
                <div class="form-group">
                <label class="form-label"><i class="text-danger">*</i> Image</label>
                <div class="preview-zone hidden">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-danger btn-sm remove-preview">
                                    <i class="text-light" data-feather="refresh-ccw"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body mt-3 mb-3">
                            @if(isset($customer))
                                <img src="{{ asset('images/identity/' . $customer->image) }}">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="dropzone-wrapper">
                    <div class="dropzone-desc">
                        <i class="glyphicon glyphicon-download-alt"></i>
                        <p>Choose image files or drag them here.</p>
                    </div>
                    <input type="file" name="image[]" accept="image/*" class="dropzone" multiple>
                </div>
            </div>

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
            $('#submitBtn').click(function(e) {
                e.preventDefault(); // Prevent default form submission

                var errors = [];
                $('#myForm input, #myForm select, #myForm textarea').each(function() {
                    if ($(this).prop('required') && !$(this).val()) {
                        errors.push($(this).prev('label').text().replace('*', '') + ' is required.');
                    }
                });

                if (errors.length > 0) {
                    Swal.fire({
                        title: 'Validation Error!',
                        html: '<ul>' + errors.map(error => `<li>${error}</li>`).join('') + '</ul>',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false, // Disable closing by clicking outside the alert
                        allowEscapeKey: false,
                    });
                } else {
                    $('#myForm').off('submit').submit(); // Allow form submission
                }
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
     <script>
        function readFiles(input) {
            if (input.files && input.files.length > 0) {
                var wrapperZone = $(input).parent();
                var previewZone = $(input).parent().parent().find('.preview-zone');
                var boxZone = $(input).parent().parent().find('.preview-zone').find('.box').find('.box-body');

                wrapperZone.removeClass('dragover');
                previewZone.removeClass('hidden');
                boxZone.empty(); // Clear previous previews

                Array.from(input.files).forEach(file => {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var htmlPreview = `
                            <div class="preview-item mb-2">
                                <img width="200" src="${e.target.result}" class="me-2" />
                                <p>${file.name}</p>
                            </div>`;
                        boxZone.append(htmlPreview);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }

        function reset(e) {
            e.wrap('<form>').closest('form').get(0).reset();
            e.unwrap();
        }

        $(".dropzone").change(function () {
            readFiles(this);
        });

        $('.dropzone-wrapper').on('dragover', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('dragover');
        });

        $('.dropzone-wrapper').on('dragleave', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
        });

        $('.remove-preview').on('click', function () {
            var boxZone = $(this).parents('.preview-zone').find('.box-body');
            var previewZone = $(this).parents('.preview-zone');
            var dropzone = $(this).parents('.form-group').find('.dropzone');
            boxZone.empty();
            previewZone.addClass('hidden');
            reset(dropzone);
        });
    </script>
@endpush
