@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="row">
            <div class="col-sm-6 mt-3 ">
                @if(isset($customer))
                    <h5 class="mb-4 ms-3">Edit Customer<i class="bx bx-edit"></i></h5>
                @else
                    <h5 class="mb-4 ms-3">Register Customer<i class="bx bx-user-plus"></i></h5>
                @endif
            </div>
            <div class="col-sm-6 mt-3">
                <a href="{{route('manager.customer.index')}}" class="btn btn-warning float-end me-3">List
                    Csutomer</a>
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
        <form class="card-body p-4" action="{{$url}}" method="POST" enctype="multipart/form-data">
            @csrf
            @isset($customer)
                @method('PUT')
            @endif
            <div class="row mb-3">
                <label for="input42" class="col-sm-3 col-form-label"><i class="text-danger">*</i> Customer Name</label>
                <div class="col-sm-9">
                    <div class="position-relative input-icon">
                        <input type="text" name="name" class="form-control" id="input42"
                               placeholder="Enter Csutomer Name" value="{{isset($customer) ? $customer->name : null}}">
                        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-user'></i></span>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="input43" class="col-sm-3 col-form-label"><i class="text-danger">*</i> Phone Whatsapp</label>
                <div class="col-sm-9">
                    <div class="position-relative input-icon">
                        <input type="number" name="phone" class="form-control" id="input43" placeholder="81XXXXXXXXXX"
                               value="{{isset($customer) ? $customer->phone : null}}">
                        <span class="position-absolute top-50 translate-middle-y"><i class='bx bxl-whatsapp'></i></span>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="input43" class="col-sm-3 col-form-label">Phone </label>
                <div class="col-sm-9">
                    <div class="position-relative input-icon">
                        <input type="number" name="phn" class="form-control" id="input43" placeholder="(optional)"
                               value="{{isset($customer) ? $customer->phn : null}}">
                        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-phone'></i></span>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="input44" class="col-sm-3 col-form-label"><i class="text-danger">*</i> No Identity</label>
                <div class="col-sm-9">
                    <div class="position-relative input-icon">
                        <input type="number" name="no_identity" class="form-control" id="input44"
                               placeholder="32XXXXXXXXXXXXX"
                               value="{{isset($customer) ? $customer->no_identity : null}}">
                        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-credit-card-front'></i></span>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="input47" class="col-sm-3 col-form-label"><i class="text-danger">*</i> Address</label>
                <div class="col-sm-9">
                    <textarea class="form-control" name="addres" id="input47" rows="3" placeholder="Address"
                              value="">{{isset($customer) ? $customer->addres : null}}</textarea>
                </div>
            </div>
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
                                <img src="{{asset('images/identity/'. $customer->image )}}">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="dropzone-wrapper">
                    <div class="dropzone-desc">
                        <i class="glyphicon glyphicon-download-alt"></i>
                        <p>Choose an image file or drag it here.</p>
                    </div>
                    <input type="file" name="image" accept="image/*" class="dropzone"
                           value="{{isset($customer) ? $customer->image : null}}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"></label>
            </div>
            <div class="d-md-flex d-grid align-items-center gap-3 float-end">
                <button type="submit" class="btn btn-dnd px-4">Save <i class="bx bx-save me-0"></i></button>
            </div>
        </form>
    </div>
    </div>
    </div>
@endsection

@push('head')
    <link rel="stylesheet" type="text/css" href="{{URL::to('assets/css/coba1.css')}}">
@endpush
@push('js')

    <script>
        function readFile(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var htmlPreview =
                        '<img width="200" src="' + e.target.result + '" />' +
                        '<p>' + input.files[0].name + '</p>';
                    var wrapperZone = $(input).parent();
                    var previewZone = $(input).parent().parent().find('.preview-zone');
                    var boxZone = $(input).parent().parent().find('.preview-zone').find('.box').find('.box-body');

                    wrapperZone.removeClass('dragover');
                    previewZone.removeClass('hidden');
                    boxZone.empty();
                    boxZone.append(htmlPreview);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function reset(e) {
            e.wrap('<form>').closest('form').get(0).reset();
            e.unwrap();
        }

        $(".dropzone").change(function () {
            readFile(this);
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
