@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Service</h5>
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
            <form class="row g-3" action="{{$url}}" method="POST" enctype="multipart/form-data" id="myForm">
                @csrf
                @isset($service)
                    @method('PUT')
                @endisset
                <div class="col-md-6">
                    <label for="input1" class="form-label"><i class="text-danger">*</i> Name Customer</label>
                    <input type="text" value="{{isset($service) ? ($service->name) : null}}" class="form-control"
                           name="name" placeholder="Enter Name Customer">
                </div>
                <div class="col-md-6">
                    <label for="input1" class="form-label">Phone Customer</label>
                    <input type="number" value="{{isset($service) ? ($service->phone) : null}}" class="form-control"
                           name="phone" placeholder="Enter Phone Customer">
                </div>
                <div class="col-md-6">
                    <label for="input" class="form-label"><i class="text-danger">*</i> Item</label>
                    <textarea type="text" class="form-control" name="item"
                              placeholder="Enter Item">{{isset($service) ? $service->item : null}}</textarea>

                </div>
                <div class="col-md-3">
                    <label for="input" class="form-label"><i class="text-danger">*</i> Type</label>
                    <textarea type="text" class="form-control" name="type"
                              placeholder="Enter Item">{{isset($service) ? $service->type : null}}</textarea>
                </div>
                <div class="col-md-3">
                    <label for="input" class="form-label"><i class="text-danger">*</i> No Seri</label>
                    <textarea type="text" class="form-control" name="no_seri"
                              placeholder="Enter Item">{{isset($service) ? $service->no_seri : null}}</textarea>
                </div>
                <div class="col-md-12">
                    <label for="input" class="form-label"><i class="text-danger">*</i> Jenis Service</label>
                    <textarea type="text" class="form-control" name="jenis_service"
                              placeholder="Enter Jenis Service">{{isset($service) ? $service->jenis_service : null}}</textarea>
                </div>
                <div class="col-md-12">
                    <label for="input" class="form-label">Accessories</label>
                    <input type="text" value="{{isset($service) ? $service->accessories : null}}"
                           class="form-control" name="accessories" placeholder="Enter Accessories">
                </div>
                <div class="col-md-12">
                    <label for="input" class="form-label"><i class="text-danger">*</i> Date Service</label>
                    <input type="text" value="{{isset($service) ? $service->date_service : null}}"
                           class="form-control datepicker" name="date_service" placeholder="Enter Date">
                </div>
                <div class="col-md-2">
                    <label for="input" class="form-label"><i class="text-danger">*</i> Price</label>
                    <input type="number" value="{{isset($service) ? $service->price : 0}}"
                           class="form-control" name="price" placeholder="Enter Price (Rp)">
                </div>
                 <div class="col-md-2">
                    <label for="input" class="form-label"><i class="text-danger">*</i> Nominal In</label>
                    <input type="number" value="{{isset($service) ? $service->nominal_in : 0}}"
                           class="form-control" name="nominal_in" placeholder="Nominal In (Rp)">
                </div>
                 <div class="col-md-2">
                    <label for="input" class="form-label">Nominal Out</label>
                    <input type="number" value="{{isset($service) ? $service->nominal_out : 0}}"
                           class="form-control" name="nominal_out" placeholder="Nominal Out (Rp)">
                </div>
                 <div class="col-md-2">
                    <label for="input" class="form-label">Biaya Ganti</label>
                    <input type="number" value="{{isset($service) ? $service->biaya_ganti : 0}}"
                           class="form-control" name="biaya_ganti" placeholder="Biaya Ganti (Rp)">
                </div>
                 <div class="col-md-2">
                    <label for="input" class="form-label">Ongkir</label>
                    <input type="number" value="{{isset($service) ? $service->ongkir : 0}}"
                           class="form-control" name="ongkir" placeholder="Ongkir (Rp)">
                </div>
                <div class="col-md-2">
                    <label for="input" class="form-label">Fee/Diskon</label>
                    <input type="number" value="{{isset($service) ? $service->diskon : 0}}"
                           class="form-control" name="diskon" placeholder="Fee/Diskon (Rp)">
                </div>
                <div class="col-md-12">
                    <label for="input" class="form-label"><i class="text-danger">*</i> Descript</label>
                    <textarea type="number" class="form-control" name="descript"
                              placeholder="Enter Descript">{{isset($service) ? $service->descript : null}}</textarea>
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
