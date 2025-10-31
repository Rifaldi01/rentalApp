@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Rental</h5>
            <form class="row g-3" action="{{$url}}" method="POST" enctype="multipart/form-data" id="myForm">
                @csrf
                @isset($rentals)
                    @method('PUT')
                @endisset
                <div class="col-md-6">
                    <label for="input6" class="form-label"><i class="text-danger">*</i> No. INV</label>
                    <input type="text" class="form-control @error('no_inv') is-invalid @enderror"
                           value="{{isset($rentals) ? $rentals->no_inv : old('no_inv')}}" name="no_inv"
                           placeholder="INV/DND/***/**">
                    @error('no_inv')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label for="input6" class="form-label"><i class="text-danger">*</i> Tanggal INV</label>
                    <input type="text" value="{{isset($rentals) ? $rentals->tgl_inv : null}} {{ old('tgl_inv') }}"
                           name="tgl_inv" class="form-control datepicker @error('tgl_inv') is-invalid @enderror"
                           id="input6" placeholder="Pembuatan">
                    @error('tgl_inv')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label for="input6" class="form-label "> Tanggal Pembayaran</label>
                    <input type="text"
                           value="{{ isset($rentals) && $rentals->debt->isNotEmpty() ? $rentals->debt->first()->date_pay : old('date_pay') }}"
                           class="form-control datepicker" name="date_pay">
                    @error('date_pay')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                @if(isset($rentals))
                    <div class="col-md-12">
                        <label for="input1" class="form-label">
                            <i class="text-danger">*</i> Name Customer
                        </label>
                        <select name="customer_id" id="single-select-field" class="form-control">
                            <option value="">--Select Customer--</option>
                            @foreach($cust as $id => $name)
                                @php
                                    $isProblem = in_array($id, $problemCustomers ?? []);
                                @endphp
                                <option value="{{ $id }}"
                                        {{ $rentals->customer_id == $id ? 'selected' : '' }}
                                        style="{{ $isProblem ? 'color:red;' : '' }}">
                                    {{ $name }} {{ $isProblem ? '(Problem)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="col-md-9">
                        <label for="input1" class="form-label">
                            <i class="text-danger">*</i> Name Customer
                        </label>
                        <select name="customer_id" id="single-select-field"
                                class="form-control {{ $errors->has('customer_id') ? 'is-invalid' : '' }}">
                            <option value="">--Select Customer--</option>
                            @foreach($cust as $id => $name)
                                @php
                                    $isProblem = in_array($id, $problemCustomers ?? []);
                                @endphp
                                <option value="{{ $id }}"
                                        {{ old('customer_id') == $id ? 'selected' : '' }}
                                        style="{{ $isProblem ? 'color:red;' : '' }}">
                                    {{ $name }} {{ $isProblem ? '(Problem)' : '' }}
                                </option>
                            @endforeach
                        </select>

                        @error('customer_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-1">
                        <div class="text-center mt-4">
                            <strong><- Atau -></strong>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label for="input2" class="form-label">Tambahkan Customer</label>
                        <div>
                            <a href="{{ route('manager.customer.create') }}" class="btn btn-dnd">
                                Tambah <i class="bx bx-user-plus"></i>
                            </a>
                        </div>
                    </div>
                @endif
                <div class="col-md-12">
                    <label for="input3" class="form-label">
                        <i class="text-danger">*</i> Item
                    </label>
                    <select name="item_id[]" id="multiple-select-field"
                            class="form-control @error('item_id') is-invalid @enderror"
                            data-placeholder="Select Item" multiple>
                        @foreach($item as $items)
                            <option value="{{ $items->id }}"
                                    @if(
                                        (isset($rentals) && in_array($items->id, json_decode($rentals->item_id, true))) ||
                                        (is_array(old('item_id')) && in_array($items->id, old('item_id')))
                                    )
                                        selected
                                @endif
                            >
                                {{ $items->name }} ({{ $items->no_seri }})
                            </option>
                        @endforeach
                    </select>
                    @error('item_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="">
                    <label for="input" class="form-label">Keterangan Item</label>
                    <textarea class="form-control"
                              name="keterangan_item">{{isset($rentals) ? $rentals->keterangan_item : old('keterangan_item')}}</textarea>
                </div>
                <div id="dynamic-fields">
                    <div class="col-md-12">
                        <button class="btn btn-dnd float-end add-field me-4 mb-2" type="button" id="add-field"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Add Accessories">Tambah Access
                            <i
                                class="bx bx-plus"></i></button>
                    </div>
                    @if(isset($rentals) && $rentals->accessoriescategory->isNotEmpty())
                        @foreach($rentals->accessoriescategory as $data)
                            <div class="input-group mt-2">
                                <div class="col-md-7">
                                    <label for="input3" class="form-label">Accessories</label>
                                    <select name="access[]" class="form-control" id="single-select-optgroup-field"
                                            data-placeholder="Choose one thing">
                                        @foreach($acces as $accessory)
                                            <option
                                                value="{{ $accessory->id }}" {{ $accessory->id == $data->accessories_id ? 'selected' : '' }}>{{ $accessory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 ms-2 me-2">
                                    <label for="input3" class="form-label">Qty</label>
                                    <input type="number" class="form-control" name="accessories_quantity[]"
                                           value="{{ $data->accessories_quantity }}" required>
                                </div>
                                <button class="btn btn-danger me-1 float-end remove-field me-3" type="button"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Accessories"><i
                                        class="bx bx-trash"></i></button>
                            </div>
                        @endforeach
                    @else
                        <div class="input-group mt-2">
                            <div class="col-md-7">
                                <label for="input3" class="form-label">Accessories</label>
                                <select name="access[]" class="form-control" id="single-select-optgroup-field"
                                        data-placeholder="Choose one thing">
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
                            <button class="btn btn-danger me-1 float-end remove-field me-3" type="button"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Accessories"><i
                                    class="bx bx-trash"></i></button>
                        </div>
                    @endif
                </div>
                <div class="">
                    <label for="input" class="form-label">Keterangan Accessories</label>
                    <textarea class="form-control"
                              name="keterangan_acces">{{isset($rentals) ? $rentals->keteranganacces : old('keterangan_acces')}}</textarea>
                </div>
                <div class="col-md-3">
                    <label for="input" class="form-label"><i class="text-danger">*</i> Total Invoce</label>
                    <input type="text"
                           value="{{isset($rentals) ? ($rentals->total_invoice) : 0}} {{ old('total_invoice') }}"
                           class="form-control @error('total_invoice') is-invalid @enderror" name="total_invoice"
                           placeholder="0">
                    @error('total_invoice')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label for="input" class="form-label"><i class="text-danger">*</i> Pembayaran</label>
                    <input type="text" value="{{isset($rentals) ? ($rentals->nominal_in) : 0}} {{ old('nominal_in') }}"
                           class="form-control @error('nominal_in') is-invalid @enderror" name="nominal_in"
                           placeholder="0">
                    @error('nominal_in')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label for="input" class="form-label">Sisa Pembayaran</label>
                    <input type="number" value="{{isset($rentals) ? $rentals->nominal_out : old('nominal_out')}}"
                           class="form-control" name="nominal_out">
                </div>
                <div class="col-md-3">
                    <label for="input" class="form-label">Fee/Discount</label>
                    <input type="number" value="{{isset($rentals) ? $rentals->diskon :0 }}{{old('diskon')}}"
                           class="form-control" name="diskon">
                </div>
                <div class="col-md-3">
                    <label for="input" class="form-label">PPN</label>
                    <input type="number" value="{{isset($rentals) ? $rentals->ppn :0 }}{{old('ppn')}}"
                           class="form-control" name="ppn">
                </div>
                <div class="col-md-3">
                    <label for="input" class="form-label">FEE</label>
                    <input type="number" value="{{isset($rentals) ? $rentals->fee :0 }}{{old('fee')}}"
                           class="form-control" name="fee">
                </div>

                <div class="col-md-12">
                    <label for="input4" class="form-label">Bank</label>
                    {{ html()->select('bank_id', $bank, isset($rentals) && $rentals->debt->isNotEmpty() ? $rentals->debt->first()->bank_id : old('bank_id'))
                            ->class(['form-control', 'is-invalid' => $errors->has('bank_id')])
                            ->id('bank-select')
                            ->placeholder("--Select Bank--")
                        }}
                </div>
                <div class="col-md-12">
                    <label for="input" class="form-label">Penerima</label>
                    <input type="text"
                           value="{{ isset($rentals) && $rentals->debt->isNotEmpty() ? $rentals->debt->first()->penerima : old('penerima') }}"
                           class="form-control" name="penerima">
                </div>
                <div class="col-md-12">
                    <label for="input4" class="form-label">Keterangan Bayar</label>
                    <textarea type="text" name="description"
                              class="form-control @error('description') is-invalid @enderror"
                              id="input4"
                              placeholder="Maukan Pembayran">{{isset($rentals) && $rentals->debt->isNotEmpty() ? $rentals->debt->first()->description : old('description')}}</textarea>
                    @error('date_pays')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="input4" class="form-label">Perusahaan</label>
                    <input type="text"
                           value="{{isset($rentals) ? $rentals->name_company : null}}{{ old('name_company') }}"
                           name="name_company" class="form-control" id="input4" placeholder="Masukan Nama Perusahaan">
                </div>
                <div class="col-md-12">
                    <label for="input5" class="form-label">Nomor Perusahaan</label>
                    <input type="number"
                           value="{{isset($rentals) ? $rentals->phone_company : null}}{{ old('phone_company') }}"
                           name="phone_company" class="form-control" id="input5" placeholder="Masukan Nomor Perusahaan">
                </div>
                <div class="col-md-12">
                    <label for="input5" class="form-label">No_PO</label>
                    <input type="text" value="{{isset($rentals) ? $rentals->no_po : null}}{{ old('no_po') }}" name="no_po"
                           class="form-control" id="input5" placeholder="Masukan No PO">
                </div>
                <div class="col-md-12">
                    <label for="input5" class="form-label">Alamat Perusahaan</label>
                    <textarea class="form-control" name="addres_company" id="input5"
                              placeholder="Masukan Alamat Perusahaan">{{isset($rentals) ? $rentals->addres_company : null}}{{ old('addres_company') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label for="input6" class="form-label"><i class="text-danger">*</i> Tanggal Mulai</label>
                    <input type="text" value="{{isset($rentals) ? $rentals->date_start : null}}{{ old('date_start') }}"
                           name="date_start" class="form-control datepicker @error('date_start') is-invalid @enderror"
                           id="input6" placeholder="Start Date">
                    @error('date_start')
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="input6" class="form-label"><i class="text-danger">*</i> Tanggal Berakhir</label>
                    <input type="text" value="{{isset($rentals) ? $rentals->date_end : null}}{{ old('date_end') }}"
                           name="date_end" class="form-control datepicker @error('date_end') is-invalid @enderror"
                           id="input6" placeholder="End Date">
                    @error('date_end')
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="input6" class="form-label"><i class="text-danger">*</i> Gamabar</label>
                    @if (isset($rentals) && $rentals->image)
                        <div class="mt-3">
                            <h6>Existing Images:</h6>
                            <div class="row">
                                @foreach (json_decode($rentals->image) as $image)
                                    <div class="col-md-2">
                                        <img src="{{ asset('images/rental/' . $image) }}" alt="Image"
                                             class="img-thumbnail mb-2">
                                        <button type="button" class="btn btn-danger btn-sm delete-image"
                                                data-image="{{ $image }}">Remove
                                        </button>
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
                        <a href="{{route('manager.rental.index')}}" class="btn btn-warning">Back</a>
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
                    '<label for="input3" class="form-label">Accessories</label>' +
                    '<select name="access[]" class="form-control single-select-clear-field" data-placeholder="Choose one thing">' +
                    '@foreach($acces as $accessory)' +
                    '@if(isset($rentals))' +
                    '<option value="{{ $accessory->id }}" @foreach($rentals->accessoriescategory as $data) @if(in_array($accessory->id,old('accessory', [$data->accessories_id])))selected="selected" @endif @endforeach>{{ $accessory->name }}</option>' +
                    '@else' +
                    '<option value="{{ $accessory->id }}">{{ $accessory->name }}</option>' +
                    '@endif' +
                    ' @endforeach' +
                    '</select>' +
                    '</div>' +
                    '<div class="col-md-1 ms-2 me-2">' +
                    '<label for="input3" class="form-label">Qty</label>' +
                    '<input type="number" class="form-control" name="accessories_quantity[]" value="{{isset($rentals) ? $rentals->accessories_quantity : null}}" required>' +
                    '</div>' +
                    '<br>' +
                    '<button class="btn btn-danger float-end remove-field" type="button" id="addButton" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Accessories"><i class="bx bx-trash"></i></button>' +
                    '</div>' +
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
    <script>
        $(document).ready(function () {
            // Handle form submission and check for validation errors
            $('#submitBtn').click(function (e) {
                e.preventDefault(); // Prevent default form submission

                var errors = [];
                $('#myForm input, #myForm select, #myForm textarea').each(function () {
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

            // Existing SweetAlert2 code for image removal
            $(document).on('click', '.delete-image', function () {
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
                            url: '{{ route('manager.rental.deleteImage') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                image: image
                            },
                            success: function (response) {
                                if (response.success) {
                                    row.remove();
                                    Swal.fire('Deleted!', 'Your image has been deleted.', 'success');
                                } else {
                                    Swal.fire('Failed!', 'Failed to remove image.', 'error');
                                }
                            },
                            error: function () {
                                Swal.fire('Error!', 'An error occurred while processing your request.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
    <script src="{{URL::to('assets/plugins/validation/jquery.validate.min.js')}}"></script>
    <script src="{{URL::to('assets/plugins/validation/validation-script.js')}}"></script>
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function () {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
    <script>
        $(document).ready(function () {
            $('#bank-select').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>
@endpush
