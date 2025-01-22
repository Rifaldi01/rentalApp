@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Service</h5>
            <form class="row g-3" action="{{$url}}" method="POST" enctype="multipart/form-data" id="myForm">
                @csrf
                @isset($service)
                    @method('PUT')
                @endisset
                <div class="col-md-6">
                    <label for="input1" class="form-label"><i class="text-danger">*</i> Nama Pelanggan</label>
                    <input type="text" value="{{isset($service) ? ($service->name) : old('name')}}" class="form-control @error('name') is-invalid @enderror"
                           name="name" placeholder="Enter Name Customer">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-3">
                <label for="input1" class="form-label"><i class="text-danger">*</i> Tanggal Invoice</label>
                    <input type="text" value="{{isset($service) ? ($service->tgl_inv) : old('tgl_inv')}}" class="form-control @error('tgl_inv') is-invalid @enderror datepicker"
                           name="tgl_inv" placeholder="Tanggal Invoice">
                    @error('tgl_inv')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label for="input1" class="form-label"><i class="text-danger">*</i> No Invoice</label>
                    <input type="text" value="{{isset($service) ? ($service->no_inv) : old('no_inv')}}" class="form-control @error('no_inv') is-invalid @enderror"
                           name="no_inv" placeholder="DND/INV/***/**">
                    @error('no_inv')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="input" class="form-label"><i class="text-danger">*</i> Item</label>
                    <textarea type="text" class="form-control @error('item') is-invalid @enderror" name="item"
                              placeholder="Enter Item">{{isset($service) ? $service->item : old('item')}}</textarea>
                    @error('item')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label for="input" class="form-label"><i class="text-danger">*</i> Type</label>
                    <textarea type="text" class="form-control @error('type') is-invalid @enderror" name="type"
                              placeholder="Enter Item">{{isset($service) ? $service->type : old('type')}}</textarea>
                    @error('type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label for="input" class="form-label"><i class="text-danger">*</i> No Seri</label>
                    <textarea type="text" class="form-control @error('no_seri') is-invalid @enderror" name="no_seri"
                              placeholder="Enter Item">{{isset($service) ? $service->no_seri : old('no_seri')}}</textarea>
                    @error('no_seri')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="input" class="form-label"><i class="text-danger">*</i> Jenis Service</label>
                    <textarea type="text" class="form-control @error('jenis_service') is-invalid @enderror" name="jenis_service"
                              placeholder="Enter Jenis Service">{{isset($service) ? $service->jenis_service : old('jenis_service')}}</textarea>
                    @error('jenis_service')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="input" class="form-label"><i class="text-danger">*</i> Tanggal Service</label>
                    <input type="text" value="{{isset($service) ? $service->date_service : old('date_service')}}"
                           class="form-control datepicker @error('date_service') is-invalid @enderror" name="date_service" placeholder="Enter Date">
                    @error('date_service')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="">Nama Bank</label>
                    {{ html()->select('bank_id', $bank, isset($rental) ? $debts->bank_id : old('bank_id'))
                            ->class(['form-control', 'is-invalid' => $errors->has('bank_id')])
                            ->id('bank-select')
                            ->placeholder("--Select Bank--")
                        }}
                </div>
                <div class="col-md-12">
                    <label for="">Nama Pemenerima</label>
                    <input type="text" class="form-control" name="penerima" value="{{old('penerima')}}">
                </div>
                <div class="col-md-2">
                    <label for="input" class="form-label"><i class="text-danger">*</i> Total Inv</label>
                    <input type="number" value="{{isset($service) ? $service->total_invoice : 0 + old('total_invoice')}}"
                           class="form-control" name="total_invoice" placeholder="Total Invoice">
                </div>
                
                 <div class="col-md-2">
                    <label for="input" class="form-label"><i class="text-danger">*</i> Uang Masuk</label>
                    <input type="number" value="{{isset($service) ? $service->nominal_in : 0 + old('nominal_in')}}"
                           class="form-control @error('nominal_in') is-invalid @enderror" name="nominal_in" placeholder="Nominal In (Rp)">
                     @error('nominal_in')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{$message}}</strong>
                        </span>
                     @enderror
                </div>
                 
                 <div class="col-md-2">
                    <label for="input" class="form-label">Sisa Bayar</label>
                    <input type="number" value="{{isset($service) ? $service->nominal_out : 0 +  old('nominal_out')}}"
                           class="form-control" name="nominal_out" placeholder="Nominal Out (Rp)">
                </div>
                 <div class="col-md-2">
                    <label for="input" class="form-label">Biaya Ganti</label>
                    <input type="number" value="{{isset($service) ? $service->biaya_ganti : 0 + old('biaya_ganti')}}"
                           class="form-control" name="biaya_ganti" placeholder="Biaya Ganti (Rp)">
                </div>
                <div class="col-md-2">
                    <label for="input" class="form-label">Fee/Diskon</label>
                    <input type="number" value="{{isset($service) ? $service->diskon : 0 + old('diskon')}}"
                           class="form-control" name="diskon" placeholder="Fee/Diskon (Rp)">
                </div>
                <div class="col-md-2">
                    <label for="input" class="form-label">Tanggal Bayar</label>
                    <input type="text" value=""
                           class="form-control datepicker " name="date_pay" placeholder="Tanggal Bayar" value="{{isset($service) ? $service->date_pay : old('date_pay')}}">
                </div>
                <div class="col-md-12">
                    <label for="input" class="form-label">Keterangan</label>
                    <textarea type="number" class="form-control @error('descript') is-invalid @enderror" name="descript"
                              placeholder="Enter Descript">{{isset($service) ? $service->descript : old('descript')}}</textarea>
                    @error('descript')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-12">
                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-primary px-4" id="submitBtn">Submit</button>
                        <a href="{{route('admin.service.index')}}" class="btn btn-warning">Back</a>
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
