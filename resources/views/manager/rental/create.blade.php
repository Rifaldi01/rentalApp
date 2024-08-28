@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Rental</h5>
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert border-0 border-start border-5 border-danger alert-dismissible fade show py-2">
                        <div class="d-flex align-rentals-center">
                            <div class="font-35 text-danger"><i class='bx bxs-message-square-x'></i></div>
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
            <form class="row g-3" action="{{$url}}" method="POST" enctype="multipart/form-data" id="myForm">
                @csrf
                @isset($rental)
                    @method('PUT')
                @endisset
                @if(isset($rental))
                    <div class="col-md-12">
                        <label for="input1" class="form-label"><i class="text-danger">*</i> Name Customer</label>
                        {{ html()->select('customer_id', $cust, isset($rental) ? $rental->customer_id : null )->class('form-control')->id('single-select-field')->placeholder("--Select Customer--") }}
                    </div>
                @else
                    <div class="col-md-9">
                        <label for="input1" class="form-label"><i class="text-danger">*</i> Name Customer</label>
                        {{ html()->select('customer_id', $cust, isset($rental) ? $rental->customer_id : null )->class('form-control')->id('single-select-field')->placeholder("--Select Customer--") }}
                    </div>
                    <div class="col-md-1">
                        <div class="text-center mt-4">
                            <strong><- OR -></strong>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="input2" class="form-label">Register Customer</label>
                        <div class="">
                            <a href="{{route('admin.customer.create')}}" class="btn btn-dnd">Add Customer<i class="bx bx-user-plus"></i></a>
                        </div>
                    </div>
                @endif
                <div class="col-md-12">
                    <label for="input3" class="form-label"><i class="text-danger">*</i> Item</label>
                    <select name="item_id[]" id="multiple-select-field" class="form-control" data-placeholder="Select Item" multiple>
                        <option value=""></option>
                        @foreach($item as $items)
                            <option value="{{ $items->id }}" 
                                    @if(isset($rental) && in_array($items->id, json_decode($rental->item_id, true))) 
                                        selected 
                                    @endif>
                                {{ $items->name }} ({{$items->no_seri}})
                            </option>
                        @endforeach
                    </select>

                </div>
                <div id="dynamic-fields">
                    <div class="col-md-12">
                        <button class="btn btn-dnd float-end add-field me-4 mb-2" type="button" id="add-field" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Accessories">Add Accessories<i class="bx bx-plus"></i></button>
                    </div>
                    @if(isset($rental) && $rental->accessoriescategory->isNotEmpty())
                        @foreach($rental->accessoriescategory as $data)
                            <div class="input-group mt-2">
                                <div class="col-md-7">
                                    <label for="input3" class="form-label">Accessories</label>
                                    <select name="access[]" class="form-control" id="single-select-optgroup-field" data-placeholder="Choose one thing">
                                        @foreach($acces as $accessory)
                                            <option value="{{ $accessory->id }}" {{ $accessory->id == $data->accessories_id ? 'selected' : '' }}>{{ $accessory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 ms-2 me-2">
                                    <label for="input3" class="form-label">Qty</label>
                                    <input type="number" class="form-control" name="accessories_quantity[]" value="{{ $data->accessories_quantity }}" required>
                                </div>
                                    <button class="btn btn-danger me-1 float-end remove-field me-3" type="button" data-bs-toggle="tooltip" data-bs-placement="top"  title="Delete Accessories"><i class="bx bx-trash"></i></button>
                            </div>
                        @endforeach
                    @else
                        <div class="input-group mt-2">
                            <div class="col-md-7">
                                <label for="input3" class="form-label">Accessories</label>
                                <select name="access[]" class="form-control" id="single-select-optgroup-field" data-placeholder="Choose one thing">
                                    @foreach($acces as $accessory)
                                        <option value="">--Select Accessories--</option>
                                        <option value="{{ $accessory->id }}">{{ $accessory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 ms-2 me-2">
                                <label for="input3" class="form-label">Qty</label>
                                <input type="number" class="form-control" name="accessories_quantity[]" required>
                            </div>
                            <button class="btn btn-danger me-1 float-end remove-field me-3" type="button" data-bs-toggle="tooltip" data-bs-placement="top"  title="Delete Accessories"><i class="bx bx-trash"></i></button>
                        </div>
                    @endif
                </div>
                <div class="col-md-3">
                    <label for="input" class="form-label"><i class="text-danger">*</i> Nominal In</label>
                    <input type="text" value="{{isset($rental) ? ($rental->nominal_in) : null}}" class="form-control" name="nominal_in" placeholder="0">
                </div>
                <div class="col-md-3">
                    <label for="input" class="form-label">Nominal Outside</label>
                    <input type="number" value="{{isset($rental) ? $rental->nominal_out : 0}}" class="form-control" name="nominal_out">
                </div>
                <div class="col-md-3">
                    <label for="input" class="form-label">Fee/Discount/Pajak</label>
                    <input type="number" value="{{isset($rental) ? $rental->diskon : 0}}" class="form-control" name="diskon">
                </div>
                <div class="col-md-3">
                    <label for="input" class="form-label">Ongkir</label>
                    <input type="number" value="{{isset($rental) ? $rental->ongkir : 0}}" class="form-control" name="ongkir">
                </div>
                <div class="col-md-12">
                    <label for="input4" class="form-label">Tanggal Bayar</label>
                    <input type="text" value="{{isset($rental) ? $rental->date_pay : null}}" name="date_pay" class="form-control" id="input4" placeholder="Enter Name Company">
                </div>
                <div class="col-md-12">
                    <label for="input4" class="form-label">Name Company</label>
                    <input type="text" value="{{isset($rental) ? $rental->name_company : null}}" name="name_company" class="form-control" id="input4" placeholder="Enter Name Company">
                </div>
                <div class="col-md-12">
                    <label for="input5" class="form-label">Phone Company</label>
                    <input type="number" value="{{isset($rental) ? $rental->phone_company : null}}" name="phone_company" class="form-control" id="input5" placeholder="Enter Phone Company">
                </div>
                <div class="col-md-12">
                    <label for="input5" class="form-label">No_PO</label>
                    <input type="text" value="{{isset($rental) ? $rental->no_po : null}}" name="no_po" class="form-control" id="input5" placeholder="Enter No PO Company">
                </div>
                <div class="col-md-12">
                    <label for="input5" class="form-label">Address Company</label>
                    <textarea class="form-control" name="addres_company" id="input5" placeholder="Enter Address Company">{{isset($rental) ? $rental->addres_company : null}}</textarea>
                </div>
                <div class="col-md-6">
                    <label for="input6" class="form-label"><i class="text-danger">*</i> Start Date</label>
                    <input type="text" value="{{isset($rental) ? $rental->date_start : null}}" name="date_start" class="form-control datepicker" id="input6" placeholder="Start Date">
                </div>
                <div class="col-md-6">
                    <label for="input6" class="form-label"><i class="text-danger">*</i> End Date</label>
                    <input type="text" value="{{isset($rental) ? $rental->date_end : null}}" name="date_end" class="form-control datepicker" id="input6" placeholder="End Date">
                </div>
                <div class="col-md-12">
                    <label for="input6" class="form-label"><i class="text-danger">*</i> Image</label>
                    @if (isset($rental) && $rental->image)
                        <div class="mt-3">
                            <h6>Existing Images:</h6>
                            <div class="row">
                                @foreach (json_decode($rental->image) as $image)
                                    <div class="col-md-2">
                                        <img src="{{ asset('images/rental/' . $image) }}" alt="Image" class="img-thumbnail mb-2">
                                        <button type="button" class="btn btn-danger btn-sm delete-image" data-image="{{ $image }}">Remove</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <!-- File input for new image upload -->
                    <input type="file" name="image[]" class="mt-2" accept="image/*" id="image-uploadify" multiple>
                </div>
                <div class="col-md-12">
                    <div class="d-md-flex d-grid align-rentals-center gap-3">
                        <button type="submit" class="btn btn-primary px-4" id="submitBtn">Submit</button>
                        <a href="{{route('admin.rental.index')}}" class="btn btn-warning">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('head')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet"/>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(".datepicker").flatpickr();
        $(".time-picker").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "Y-m-d H:i",
        });
        $(".date-time").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
        $(".date-format").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
        $(".date-range").flatpickr({
            mode: "range",
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
        $(".date-inline").flatpickr({
            inline: true,
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
    </script>
    <script>
        $(document).ready(function () {
            // Inisialisasi select2 pada elemen yang ada


            // Tambah kolom baru
            $('#add-field').click(function () {
                $('#dynamic-fields').append(
                    '<div class="input-group mt-2">' +
                    '<div class="col-md-7">' +
                    '<label for="input3" class="form-label">Accessories</label>'+
                    '<select name="access[]" class="form-control single-select-clear-field" data-placeholder="Choose one thing">' +
                    '@foreach($acces as $accessory)'+
                    '@if(isset($rental))' +
                    '<option value="{{ $accessory->id }}" @foreach($rental->accessoriescategory as $data) @if(in_array($accessory->id,old('accessory', [$data->accessories_id])))selected="selected" @endif @endforeach>{{ $accessory->name }}</option>'+
                    '@else' +
                    '<option value="{{ $accessory->id }}">{{ $accessory->name }}</option>' +
                    '@endif' +
                    ' @endforeach' +
                    '</select>' +
                    '</div>' +
                    '<div class="col-md-1 ms-2 me-2">' +
                    '<label for="input3" class="form-label">Qty</label>'+
                    '<input type="number" class="form-control" name="accessories_quantity[]" value="{{isset($rental) ? $rental->accessories_quantity : null}}" required>' +
                    '</div>' +
                    '<br>'+
                    '<button class="btn btn-danger float-end remove-field" type="button" id="addButton" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Accessories"><i class="bx bx-trash"></i></button>' +
                    '</div>'+
                    '</div>'
                );

                // Inisialisasi select2 pada elemen yang baru ditambahkan
                $('.single-select-clear-field').last().select2();
            });

            // Hapus kolom
            $(document).on('click', '.remove-field', function () {
                $(this).closest('.input-group').remove();
            });
        });
    </script>
@endpush
