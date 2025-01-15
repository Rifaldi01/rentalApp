@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-6 mt-3">
                    <div class="container">
                        <h4 class="text-uppercase">Pembayaran Belum Lunas</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="excel" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th class="text-center" width="5%">Invoice</th>
                        <th>Customer</th>
                        <th class="text-center" width="5%">Total Seharusnya</th>
                        <th class="text-center" width="5%">Uang Masuk</th>
                        <th class="text-center" width="5%">Sisa Bayar</th>
                        <th class="text-center" width="5%">Diskon</th>
                        <th class="text-center" width="5%">Total</th>
                        <th class="text-center" width="5%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rental as $key => $data)
                        @if($data->nominal_in == $data->pay)
                        @else
                            <tr>
                                <td data-index="{{ $key + 1 }}">{{$key +1}}</td>
                                <td>{{$data->no_inv}}</td>
                                <td>{{$data->cust->name}}</td>
                                <td class="text-center">{{formatRupiah($totalseharusnya[$data->id])}}</td>
                                <td>{{formatRupiah($data->nominal_in)}}</td>
                                <td>{{formatRupiah($data->nominal_out)}}</td>
                                <td>{{formatRupiah($data->diskon)}}</td>
                                <td>{{formatRupiah($total[$data->id])}}</td>
                                <td class="text-center">
                                    <button class="btn btn-warning lni lni-dollar btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#examplemodal{{$data->id}}" data-bs-tool="tooltip"
                                            data-bs-placement="top" title="Bayar">
                                    </button>
                                    <div class="modal fade" id="examplemodal{{$data->id}}" tabindex="-1"
                                         aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Pembayaran</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <form action="{{route('admin.pembayaran.bayar', $data->id)}}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <label for="input42" class="col-sm-3 col-form-label"><i
                                                                    class="text-danger">*</i> Uang Masuk</label>
                                                            <div class="col-sm-9">
                                                                <div class="position-relative input-icon">
                                                                    <input type="hidden" id="nominal_in_value_{{$data->id}}"
                                                                           value="{{ $data->nominal_in }}">
                                                                    <input type="text" class="form-control"
                                                                           id="nominal_in_{{$data->id}}" name="nominal_in"
                                                                           value="{{ formatRupiah($data->nominal_in) }}"
                                                                           readonly>
                                                                    <span
                                                                        class="position-absolute top-50 translate-middle-y"><i
                                                                            class='bx bx-dollar'></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="input42" class="col-sm-3 col-form-label"><i
                                                                    class="text-danger">*</i> Pay Debts</label>
                                                            <div class="col-sm-9">
                                                                <div class="position-relative input-icon">
                                                                    <input type="text" class="form-control"
                                                                           name="pay_debts" id="pay_debts_{{$data->id}}"
                                                                           onkeyup="formatRupiah2(this)">
                                                                    <span
                                                                        class="position-absolute top-50 translate-middle-y"><i
                                                                            class='bx bx-money'></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="input42" class="col-sm-3 col-form-label"><i
                                                                    class="text-danger">*</i> Date</label>
                                                            <div class="col-sm-9">
                                                                <div class="position-relative input-icon">
                                                                    <input type="text" class="form-control datepicker"
                                                                           name="date_pay" id="input42">
                                                                    <span
                                                                        class="position-absolute top-50 translate-middle-y"><i
                                                                            class='bx bx-calendar'></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3" id="bankField_{{$data->id}}">
                                                            <label for="input42"
                                                                   class="col-sm-3 col-form-label">Bank</label>
                                                            <div class="col-sm-9">
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-text"><i
                                                                            class="bx bx-credit-card"></i></div>
                                                                    <select class="form-select" id="single-select-field"
                                                                            name="bank_id"
                                                                            data-placeholder="-- Nama Bank --">
                                                                        <option></option>
                                                                        @foreach($bank as $banks)
                                                                            <option
                                                                                value="{{$banks->id}}">{{$banks->name}}
                                                                                ({{$banks->code}})
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="input42" class="col-sm-3 col-form-label"><i
                                                                    class="text-danger"></i> </label>
                                                            <div class="col-sm-9">
                                                                <div class="position-relative input-icon">
                                                                    <input type="checkbox" class="form-check"
                                                                           id="lainya_{{$data->id}}">
                                                                    <span class="position-absolute top-50 translate-middle-y ms-1"> Lainya</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3" id="descriptionField">
                                                            <label for="input42"
                                                                   class="col-sm-3 col-form-label"></label>
                                                            <div class="col-sm-9">
                                                                <textarea id="description_{{$data->id}}" type="text"
                                                                          class="form-control" name="description"
                                                                          placeholder="Isi Lainya pembayaran melalui apa?"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-primary" id="bayarbutton">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-6 mt-3">
                    <div class="container">
                        <h4 class="text-uppercase">Daftar Pembayaran</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
        <div class="row">
                <form action="{{route('admin.pembayaran.filter')}}" method="GET">
                    <div class="row">
                        <div class="col-5 ms-2 mt-2">
                            <label class="form-label">
                                Start Date
                            </label>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">
                                End Date
                            </label>
                            <input type="date" class="form-control" name="end_date" required>
                        </div>
                    </div>
                    <div class="col-md-1 pt-4 float-end me-5">
                        <button type="submit" class="btn btn-success">Filter</button>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table id="transaction" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th>Tanggal Bayar</th>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Uang Masuk</th>
                        <th>Keterangan Bayar</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($debt as $key => $data)
                        
                            <tr>
                                <td class="text-center">{{$key+1}}</td>
                                <td>{{formatId($data->date_pay)}}</td>
                                <td>{{$data->rental->no_inv}}</td>
                                <td>{{$data->rental->cust->name}}</td>
                                <td>{{formatRupiah($data->pay_debts)}}</td>
                                <td>{{$data->bank->name}}</td>
                            </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
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
            var table = $('#excel').DataTable({
                lengthChange: false,
                buttons: ['excel'],
                paginate: false
            });

            table.buttons().container()
                .appendTo('#excel_wrapper .col-md-6:eq(0)');
            table.on('order.dt search.dt', function () {
                let i = 1;
                table.cells(null, 0, {search: 'applied', order: 'applied'}).every(function (cell) {
                    this.data(i++);
                });
            }).draw();
        });
    </script>
    
    <script>
       $(document).ready(function () {
        var table = $('#transaction').DataTable({
            lengthChange: false,
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel',
                    title: function() {
                        var currentDate = new Date();
                        var formattedDate = currentDate.toLocaleDateString('id-ID'); // Format tanggal sesuai lokal Indonesia
                        return 'Laporan_' + formattedDate; // Nama file sesuai tanggal
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title: function() {
                        var currentDate = new Date();
                        var formattedDate = currentDate.toLocaleDateString('id-ID');
                        return 'Laporan_' + formattedDate;
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: function() {
                        var currentDate = new Date();
                        var formattedDate = currentDate.toLocaleDateString('id-ID');
                        return 'Laporan_' + formattedDate;
                    }
                }
            ],
        });


            table.buttons().container()
                .appendTo('#transaction_wrapper .col-md-6:eq(0)');
            table.on('order.dt search.dt', function () {
                let i = 1;
                table.cells(null, 0, {search: 'applied', order: 'applied'}).every(function (cell) {
                    this.data(i++);
                });
            }).draw();
        });
    </script>
    <script>
        $(document).ready(function () {
            // Inisialisasi Select2 setelah modal dibuka
            $(document).on('shown.bs.modal', function (e) {
                let modal = $(e.target); // Modal yang sedang ditampilkan
                modal.find('#single-select-field').select2({
                    dropdownParent: modal, // Tetapkan parent dropdown ke modal yang aktif
                    placeholder: '-- Nama Bank --',
                    allowClear: true,
                    theme: 'bootstrap-5'
                });
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            function calculateTotal(id) {
                let nominal_in = parseFloat($(`#nominal_in_value_${id}`).val()) || 0;
                let pay_debts = parseFloat($(`#pay_debts_${id}`).val().replace(/[^0-9]/g, '')) || 0;

                let total = nominal_in + pay_debts;
                $(`#nominal_in_${id}`).val('Rp. ' + total.toLocaleString('id-ID'));
            }

            $('[id^=pay_debts_]').on('input', function () {
                let id = $(this).attr('id').split('_')[2];
                calculateTotal(id);
            });

            $('[id^=nominal_in_value_]').each(function () {
                let id = $(this).attr('id').split('_')[2];
                calculateTotal(id);
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#submitBtn').click(function (event) {
                // Nonaktifkan tombol dan ubah teksnya
                $(this).prop('bayarbutton', true).text('Memuat...');
            });
        });

        function formatRupiah2(element) {
            let value = element.value.replace(/[^,\d]/g, '');
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            element.value = rupiah;

            function toggleValidation(id) {
            if ($(`#lainya_${id}`).is(':checked')) {
                // Jika checkbox lainya dicentang:
                $(`#description_${id}`).prop('required', true); // Description wajib diisi
                $(`#bankField_${id}`).hide(); // Sembunyikan bank field
                $(`#single-select-field_${id}`).prop('required', false); // Bank tidak wajib
            } else {
                // Jika checkbox lainya tidak dicentang:
                $(`#description_${id}`).prop('required', false); // Description tidak wajib
                $(`#bankField_${id}`).show(); // Tampilkan bank field
                $(`#single-select-field_${id}`).prop('required', true); // Bank wajib diisi
            }
        }

        // Event listener untuk checkbox lainya
        $("[id^='lainya_']").on('change', function () {
            var id = $(this).attr('id').split('_')[1]; // Ambil ID dinamis
            toggleValidation(id);
        });

        // Inisialisasi validasi saat halaman dimuat
        $("[id^='lainya_']").each(function () {
            var id = $(this).attr('id').split('_')[1]; // Ambil ID dinamis
            toggleValidation(id);
        });
        
        }
    </script>

@endpush

