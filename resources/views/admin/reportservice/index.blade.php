@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="col">
                <div class="row">
                    <div class="col-sm">
                        <h4 class="mb-0 text-uppercase">
                            Service Report
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr/>
    <div class="card table-timbang">
        <div class="card-head">
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
            <div class="row">
                <form action="{{route('admin.service.filter')}}" method="GET">
                    <div class="row">
                        <div class="col-5 ms-2 mt-2">
                            <label class="form-label">
                                Start Date
                            </label>
                            <input type="date" class="form-control" name="start_date"  required>
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">
                                End Date
                            </label>
                            <input type="date" class="form-control" name="end_date"  required>
                        </div>
                    </div>
                    <div class="col-md-1 pt-4 float-end me-5">
                        <button type="submit" class="btn btn-success">Filter</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example3" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th>Name</th>
                        <th>Item</th>
                        <th>No Seri</th>
                        <th>Accessories</th>
                        <th>Date Service</th>
                        <th>Price</th>
                        <th>Nominal <br>In</th>
                        <th>Nominal <br>Outsid</th>
                        <th>Fee/ <br>Diskon</th>
                        <th>Ongkir</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($report as $key => $data)
                        <tr>
                            <td>{{$key +1}}</td>
                            <td>{{$data->name}}</td>
                            <td>{{$data->item}}</td>
                            <td>{{$data->no_seri}}</td>
                            <td>{{$data->accessories}}</td>
                            <td>{{formatId($data->date_service)}}</td>
                            <td>{{formatRupiah($data->price)}},-</td>
                            <td>{{formatRupiah($data->nominal_in)}}</td>
                            <td>{{formatRupiah($data->nominal_out)}}</td>
                            <td>{{formatRupiah($data->diskon)}}</td>
                            <td>{{formatRupiah($data->ongkir)}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="col">
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th> <h5 class="mb-0 text-uppercase">Total Income</h5></th>
                            <td><h5>:</h5></td>
                            <td><h5 class="ms-2">{{formatRupiah($totalincome)}},-</h5></td>
                        </tr>
                        <tr>
                            <th> <h5 class="mb-0 text-uppercase">Total Nominal Outside</h5></th>
                            <td><h5>:</h5></td>
                            <td><h5 class="ms-2">{{formatRupiah($totaloutside)}},-</h5></td>
                        </tr>
                        <tr>
                            <th> <h5 class="mb-0 text-uppercase">Total Fee/Diskon</h5></th>
                            <td><h5>:</h5></td>
                            <td><h5 class="ms-2">{{formatRupiah($totaldiskon)}},-</h5></td>
                        </tr>
                        <tr>
                            <th> <h5 class="mb-0 text-uppercase">Total Ongkir</h5></th>
                            <td><h5>:</h5></td>
                            <td><h5 class="ms-2">{{formatRupiah($totalongkir)}},-</h5></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('head')

@endpush

@push('js')

@endpush
