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
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Add Service"
                           href="{{route('admin.service.create')}}"
                           class="btn btn-dnd float-end me-3 mt-3 btn-sm shadow"><i
                                class="bx bx-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th width="2%">No</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Item</th>
                            <th>No Seri</th>
                            <th>Accessories</th>
                            <th>Date Service</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($history as $key => $data) @endforeach
                        <tr>
                            <td>{{$key +1}}</td>
                            <td>{{$data->name}}</td>
                            <td>{{$data->phone}}</td>
                            <td>{{$data->item}}</td>
                            <td>{{$data->no_seri}}</td>
                            <td>{{$data->accessories}}</td>
                            <td>{{dateId($data->date_service)}}</td>
                            <td>{{formatRupiah($data->price)}},-</td>
                            <td>
                                @if($data->status == 0)
                                    <span class="badge bg-success">Service</span>
                                @else($data->status == 1)
                                    <span class="badge bg-secondary">Finished</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-warning lni lni-eye" data-bs-toggle="modal"
                                        data-bs-placement="top"
                                        title="Detail" data-bs-target="#exampleLargeModal{{$data->id}}"></button>
                                <div class="modal fade" id="exampleLargeModal{{$data->id}}" tabindex="-1"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Service</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <table id="" class="table table-bordered">
                                                        <tr>
                                                            <th width="5%">
                                                                <div class="float-start">Name Customer</div>
                                                            </th>
                                                            <td>
                                                                <div class="float-start">{{$data->name}}</div>
                                                            </td>
                                                            <th width="5%">
                                                                <div class="float-start">Name Sales</div>
                                                            </th>
                                                            <td>
                                                                <div class="float-start">{{$data->name_sales}}</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <div class="float-start">Phone Customer</div>
                                                            </th>
                                                            <td>
                                                                <div class="float-start">{{$data->phone}}</div>
                                                            </td>
                                                            <th>
                                                                <div class="float-start">Phone Sales</div>
                                                            </th>
                                                            <td>
                                                                <div class="float-start">{{$data->phone_sales}}</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-center" colspan="4">ITEM</th>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <div class="float-start">Name Item</div>
                                                            </th>
                                                            <th>
                                                                <div class="float-start">No Seri</div>
                                                            </th>
                                                            <th>
                                                                <div class="float-start">Date Produksi</div>
                                                            </th>
                                                            <th>
                                                                <div class="float-start">Date Pemebelian</div>
                                                            </th>
                                                        </tr>
                                                        <tr>

                                                            <td>
                                                                <div class="float-start">{{$data->item}}</div>
                                                            </td>
                                                            <td>
                                                                <div class="float-start">{{$data->no_seri}}</div>
                                                            </td>
                                                            <td>
                                                                <div
                                                                    class="float-start">{{dateId($data->date_produksi)}}</div>
                                                            </td>
                                                            <td>
                                                                <div
                                                                    class="float-start">{{dateId($data->date_pembelian)}}</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <div class="float-start">Accessories</div>
                                                            </th>
                                                            <td colspan="3">
                                                                <div class="float-start">{{($data->accessories)}}</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <div class="float-start">Jenis Service</div>
                                                            </th>
                                                            <td colspan="3">
                                                                <div class="float-start">{{($data->accessories)}}</div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="4">
                                                                <div class="text-center">DATE</div>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="2" class="text-center">Date Service</th>
                                                            <th colspan="2" class="text-center">Date Finish</th>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"
                                                                class="text-center">{{dateId($data->date_service)}}</td>
                                                            <td colspan="2" class="text-center">
                                                                @if($data->date_finis)
                                                                    {{dateId($data->date_finis)}}
                                                                @else
                                                                    <i class="text-danger">Service Belum Selesai</i>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <div class="float-start">Descript</div>
                                                            </th>
                                                            <td colspan="3">
                                                                {{$data->descript}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <div class="float-start">Status</div>
                                                            </th>
                                                            <td colspan="3">
                                                                @if($data->status == 0)
                                                                    <span
                                                                        class="badge bg-success float-start">Service</span>
                                                                @else
                                                                    <span class="badge bg-secondary float-start">Finished</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <div class="float-start">Price</div>
                                                            </th>
                                                            <td colspan="3">
                                                                {{formatRupiah($data->price)}},-
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-success lni lni-checkmark" data-bs-toggle="modal"
                                        data-bs-placement="top" title="Finished"
                                        data-bs-target="#exampleVerticallycenteredModal{{$data->id}}"></button>
                                <div class="modal fade" id="exampleVerticallycenteredModal{{$data->id}}" tabindex="-1"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Date Finsished</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <form action="{{route('admin.service.update', $data->id)}}"
                                                  method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <input value="{{$data->id}}" type="hidden" name="rental_id"
                                                           class="form-control">
                                                    <label class="col-form-label">Date Finshed</label>
                                                    <input type="text"
                                                           class="form-control datepicker" name="date_finis"
                                                           placeholder="Enter Date">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">Save<i
                                                            class="bx bx-save"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
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
