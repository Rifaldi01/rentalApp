@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Pinjaman Divisi</h5>
            <form class="row g-3" action="{{$url}}" method="POST" enctype="multipart/form-data" id="myForm">
                @csrf
                @isset($rentalDivisi)
                    @method('PUT')
                @endisset

                @if(isset($rentalDivisi))
                    <div class="col-md-12">
                        <label for="input1" class="form-label"><i class="text-danger">*</i> Name Divisi</label>
                        {{ html()->select('divisi_id', $divisi, isset($rentalDivisi) ? $rentalDivisi->divisi_id : null )->class('form-control')->id('single-select-field')->placeholder("--Select Divisi--") }}
                    </div>
                @else
                    <div class="col-md-12">
                        <label for="input1" class="form-label"><i class="text-danger">*</i> Name Divisi</label>
                        {{ html()->select('divisi_id', $divisi, isset($rentalDivisi) ? $rentalDivisi->customer_id : old('customer_id'))
                            ->class(['form-control', 'is-invalid' => $errors->has('customer_id')])
                            ->id('single-select-field')
                            ->placeholder("--Select Divisi--")
                        }}
                        @error('customer_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
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
                                        (isset($rentalDivisi) && in_array($items->id, json_decode($rentalDivisi->item_id, true))) ||
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
                <div id="dynamic-fields">
                    <div class="col-md-12">
                        <button class="btn btn-dnd float-end add-field me-4 mb-2" type="button" id="add-field"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Add Accessories">Tambah Access
                            <i
                                class="bx bx-plus"></i></button>
                    </div>
                    @if(isset($rentalDivisi) && $rentalDivisi->accessoriescategory->isNotEmpty())
                        @foreach($rentalDivisi->accessoriescategory as $data)
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
                                <input type="number" class="form-control" name="qty[]" required>
                            </div>
                            <button class="btn btn-danger me-1 float-end remove-field me-3" type="button"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Accessories"><i
                                    class="bx bx-trash"></i></button>
                        </div>
                    @endif
                </div>
                <div class="dynamic-fields">
                    <label for="input1" class="form-label">Keterangan</label>
                    <textarea name="description" class="form-control" cols="30" rows="10">{{isset($rentalDivisi) ? $rentalDivsisi->description : null}}{{ old('description') }}</textarea>
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
                    '<label for="input3" class="form-label">Accessories</label>' +
                    '<select name="access[]" class="form-control single-select-clear-field" data-placeholder="Choose one thing">' +
                    '@foreach($acces as $accessory)' +
                    '@if(isset($rentalDivisi))' +
                    '<option value="{{ $accessory->id }}" @foreach($rentalDivisi->accessoriescategory as $data) @if(in_array($accessory->id,old('accessory', [$data->accessories_id])))selected="selected" @endif @endforeach>{{ $accessory->name }}</option>' +
                    '@else' +
                    '<option value="{{ $accessory->id }}">{{ $accessory->name }}</option>' +
                    '@endif' +
                    ' @endforeach' +
                    '</select>' +
                    '</div>' +
                    '<div class="col-md-1 ms-2 me-2">' +
                    '<label for="input3" class="form-label">Qty</label>' +
                    '<input type="number" class="form-control" name="accessories_quantity[]" value="{{isset($rentalDivisi) ? $rentalDivisi->qty : null}}" required>' +
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
                            url: '{{ route('admin.rental.deleteImage') }}',
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
