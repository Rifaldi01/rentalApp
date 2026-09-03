@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-md-6">
                    <div class="container mt-3">
                        <h4 class="text-uppercase">List Service</h4>
                    </div>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('marketing.service') }}" method="GET" class="mb-3">
                        <div class="row align-items-end">

                            <div class="col-md-4">
                                <label class="form-label">Bulan</label>
                                <select name="bulan" class="form-select">
                                    @foreach(range(1, 12) as $i)
                                        <option value="{{ $i }}" {{ (int) $bulan === $i ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tahun</label>
                                <select name="tahun" class="form-select">
                                    @for($i = now()->year; $i >= now()->year - 5; $i--)
                                        <option value="{{ $i }}" {{ (int) $tahun === $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filter
                                </button>

                                <a href="{{ route('marketing.service') }}" class="btn btn-secondary">
                                    <i class="fas fa-sync-alt"></i> Reset
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th>Invoice</th>
                        <th>Pelanggan</th>
                        <th>Nama Alat</th>
                        <th>No Seri</th>
                        <th>Type</th>
                        <th>Total Inv</th>
                        <th>Biaya Ganti</th>
                        <th>PPN</th>
                        <th>Uang Masuk</th>
                        <th>Sisa Bayar</th>
                        <th>Tgl Servis</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($service as $key => $data)
                        <tr>
                            <td>{{$key +1}}</td>
                            <td>{{$data->no_inv}}</td>
                            <td>
                                @if($data->name)
                                    {{$data->name}}
                                @else
                                    {{$data->cust->name}}
                                @endif
                            </td>
                            <td>@foreach(explode(',', $data->item) as $item)
                                    <li>{{ trim($item) }}</li>
                                @endforeach
                            </td>
                            <td>@foreach(explode(',', $data->no_seri) as $no_seri)
                                    <li>{{ trim($no_seri) }}</li>
                                @endforeach
                            </td>
                            <td>
                                @foreach(explode(',', $data->type) as $type)
                                    <li>{{ trim($type) }} </li>
                                @endforeach
                            </td>
                            <td>{{formatRupiah($data->total_invoice)}}</td>
                            <td>{{formatRupiah($data->biaya_ganti)}}</td>
                            <td>{{formatRupiah($data->ppn)}}</td>
                            <td>{{formatRupiah($data->nominal_in)}}</td>
                            <td class="text-center">
                                @if($data->nominal_out == 0)
                                    <span class="badge bg-primary">Lunas</span>
                                @else
                                    {{formatRupiah($data->nominal_out)}}
                                @endif
                            </td>
                            <td>{{formatId($data->date_service)}}</td>
                            <td>
                                @if($data->status == 0)
                                    <span class="badge bg-success">Service</span>
                                @else($data->status == 1)
                                    <span class="badge bg-secondary">Finished</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <link href="{{URL::to('assets/css/flatpickr.min.css')}}" rel="stylesheet"/>
@endpush
@push('js')
    <script src="{{URL::to('assets/js/flatpickr.min.js')}}"></script>

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

        $(document).ready(function () {
            $('#bayarbutton').click(function (event) {
                // Nonaktifkan tombol dan ubah teksnya
                $(this).prop('disabled', true).text('Memuat...');

                $('#myForm').submit();
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
                    $(`#penerimaField_${id}`).hide(); // Sembunyikan bank field
                    $(`#single-select-field_${id}`).prop('required', false); // Bank tidak wajib
                    $(`#penerima_${id}`).prop('required', false); // Bank tidak wajib
                } else {
                    // Jika checkbox lainya tidak dicentang:
                    $(`#description_${id}`).prop('required', false); // Description tidak wajib
                    $(`#bankField_${id}`).show(); // Tampilkan bank field
                    $(`#penerimaField_${id}`).show(); // Tampilkan bank field
                    $(`#single-select-field_${id}`).prop('required', true); // Bank wajib diisi
                    $(`#penerima${id}`).prop('required', true); // Bank wajib diisi
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

        //delete

    </script>
@endpush
