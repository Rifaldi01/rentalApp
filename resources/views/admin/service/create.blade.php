@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Service</h5>
            <form class="row g-3" action="{{ $url }}" method="POST" enctype="multipart/form-data" id="myForm">
                @csrf
                @isset($service)
                    @method('PUT')
                @endisset

                {{-- Nama Pelanggan --}}
                @if(isset($service))
                    <div class="col-md-6">
                        <label for="input1" class="form-label"><i class="text-danger">*</i> Name Customer</label>
                        {{ html()->select('customer_id', $cust, isset($service) ? $service->customer_id : null )->class('form-control')->id('single-select-field')->placeholder("--Select Customer--") }}
                    </div>
                @else
                    <div class="col-md-6">
                        <label for="input1" class="form-label"><i class="text-danger">*</i> Name Customer</label>
                        {{ html()->select('customer_id', $cust, isset($service) ? $service->customer_id : old('customer_id'))
                            ->class(['form-control', 'is-invalid' => $errors->has('customer_id')])
                            ->id('single-select-field')
                            ->placeholder("--Select Customer--")
                        }}
                        @error('customer_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                @endif

                {{-- Tanggal Invoice --}}
                <div class="col-md-3">
                    <label class="form-label"><i class="text-danger">*</i> Tanggal Invoice</label>
                    <input type="text"
                           value="{{ isset($service) ? $service->tgl_inv : old('tgl_inv') }}"
                           class="form-control datepicker @error('tgl_inv') is-invalid @enderror"
                           name="tgl_inv" placeholder="Tanggal Invoice">
                    @error('tgl_inv') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                {{-- No Invoice --}}
                <div class="col-md-3">
                    <label class="form-label"><i class="text-danger">*</i> No Invoice</label>
                    <input type="text"
                           value="{{ isset($service) ? $service->no_inv : old('no_inv') }}"
                           class="form-control @error('no_inv') is-invalid @enderror"
                           name="no_inv" placeholder="DND/INV/***/**">
                    @error('no_inv') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                {{-- Item --}}
                <div class="col-md-6">
                    <label class="form-label"><i class="text-danger">*</i> Item</label>
                    <textarea class="form-control @error('item') is-invalid @enderror"
                              name="item" placeholder="Enter Item">{{ isset($service) ? $service->item : old('item') }}</textarea>
                    @error('item') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                {{-- Type --}}
                <div class="col-md-3">
                    <label class="form-label"><i class="text-danger">*</i> Type</label>
                    <textarea class="form-control @error('type') is-invalid @enderror"
                              name="type" placeholder="Enter Type">{{ isset($service) ? $service->type : old('type') }}</textarea>
                    @error('type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                {{-- No Seri --}}
                <div class="col-md-3">
                    <label class="form-label"><i class="text-danger">*</i> No Seri</label>
                    <textarea class="form-control @error('no_seri') is-invalid @enderror"
                              name="no_seri" placeholder="Enter No Seri">{{ isset($service) ? $service->no_seri : old('no_seri') }}</textarea>
                    @error('no_seri') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                {{-- Jenis Service --}}
                <div class="col-md-12">
                    <label class="form-label"><i class="text-danger">*</i> Jenis Service</label>
                    <textarea class="form-control @error('jenis_service') is-invalid @enderror"
                              name="jenis_service" placeholder="Enter Jenis Service">{{ isset($service) ? $service->jenis_service : old('jenis_service') }}</textarea>
                    @error('jenis_service') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                {{-- Tanggal Service --}}
                <div class="col-md-12">
                    <label class="form-label"><i class="text-danger">*</i> Tanggal Service</label>
                    <input type="text"
                           value="{{ isset($service) ? $service->date_service : old('date_service') }}"
                           class="form-control datepicker @error('date_service') is-invalid @enderror"
                           name="date_service" placeholder="Enter Date">
                    @error('date_service') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                {{-- Nama Bank --}}
                <div class="col-md-12">
                    <label>Nama Bank</label>
                    {{ html()->select(
                        'bank_id',
                        $bank,
                        isset($service) && $service->debt && $service->debt->isNotEmpty()
                            ? $service->debt->first()->bank_id
                            : old('bank_id')
                    )
                    ->class(['form-control', 'is-invalid' => $errors->has('bank_id')])
                    ->id('bank-select')
                    ->placeholder("--Select Bank--") }}
                </div>

                {{-- Nama Penerima --}}
                <div class="col-md-12">
                    <label>Nama Penerima</label>
                    <input type="text" class="form-control" name="penerima"
                           value="{{ isset($service) && $service->debt && $service->debt->isNotEmpty()
                        ? $service->debt->first()->penerima
                        : old('penerima') }}">
                </div>

                {{-- Keterangan Bayar --}}
                <div class="col-md-12">
                    <label>Keterangan Bayar</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              name="description" placeholder="Keterangan Bayar">{{ old('description') }}</textarea>
                    @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                {{-- Total Inv --}}
                <div class="col-md-2">
                    <label><i class="text-danger">*</i> Total Inv</label>
                    <input type="number"
                           value="{{ isset($service) ? $service->total_invoice : old('total_invoice', 0) }}"
                           class="form-control" name="total_invoice" placeholder="Total Invoice">
                </div>

                {{-- Uang Masuk --}}
                <div class="col-md-2">
                    <label><i class="text-danger">*</i> Uang Masuk</label>
                    <input type="number"
                           value="{{ isset($service) ? $service->nominal_in : old('nominal_in', 0) }}"
                           class="form-control @error('nominal_in') is-invalid @enderror"
                           name="nominal_in" placeholder="Nominal In (Rp)">
                    @error('nominal_in') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                {{-- Sisa Bayar --}}
                <div class="col-md-2">
                    <label>Sisa Bayar</label>
                    <input type="number"
                           value="{{ isset($service) ? $service->nominal_out : old('nominal_out', 0) }}"
                           class="form-control" name="nominal_out" placeholder="Nominal Out (Rp)">
                </div>

                {{-- Biaya Ganti --}}
                <div class="col-md-2">
                    <label>Biaya Ganti</label>
                    <input type="number"
                           value="{{ isset($service) ? $service->biaya_ganti : old('biaya_ganti', 0) }}"
                           class="form-control" name="biaya_ganti" placeholder="Biaya Ganti (Rp)">
                </div>

                {{-- Fee/Diskon --}}
                <div class="col-md-2">
                    <label>Fee/Diskon</label>
                    <input type="number"
                           value="{{ isset($service) ? $service->diskon : old('diskon', 0) }}"
                           class="form-control" name="diskon" placeholder="Fee/Diskon (Rp)">
                </div>

                {{-- PPN --}}
                <div class="col-md-2">
                    <label>PPN</label>
                    <input type="number"
                           value="{{ isset($service) ? $service->ppn : old('ppn', 0) }}"
                           class="form-control" name="ppn" placeholder="PPN (Rp)">
                </div>

                {{-- Tanggal Bayar --}}
                <div class="col-md-2">
                    <label>Tanggal Bayar</label>
                    <input type="text" class="form-control datepicker"
                           name="date_pay"
                           value="{{ isset($service) && $service->debt ? $service->debt->date_pay : old('date_pay') }}"
                           placeholder="Tanggal Bayar">
                </div>

                {{-- Keterangan --}}
                <div class="col-md-12">
                    <label>Keterangan Ganti</label>
                    <textarea class="form-control @error('descript') is-invalid @enderror"
                              name="descript" placeholder="Enter Descript">{{ isset($service) ? $service->descript : old('descript') }}</textarea>
                    @error('descript') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                {{-- Buttons --}}
                <div class="col-md-12">
                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-primary px-4" id="submitBtn">Submit</button>
                        <a href="{{ route('admin.service.index') }}" class="btn btn-warning">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('head')
    <link href="{{URL::to('assets/css/flatpickr.min.css')}}" rel="stylesheet"/>
@endpush
@push('js')
    <script src="{{URL::to('assets/js/flatpickr.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#submitBtn').click(function() {
                // Disable button dan ubah teksnya
                $(this).prop('disabled', true).text('Loading...');

                // Kirim form secara manual
                $('#myForm').submit();
            });
        });
        $(document).ready(function () {
            $('#bank-select').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>
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
@endpush
