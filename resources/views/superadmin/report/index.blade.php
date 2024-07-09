@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="col">
                <div class="row">
                    <div class="col-sm">
                        <h4 class="mb-0 text-uppercase">Rentals Report
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr/>
    <div class="card table-timbang">
        <div class="card-head">
            <div class="row">
                <form action="{{route('report.filter')}}" method="GET">
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
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th width="">Nominal <br>In</th>
                        <th>Nominal <br>outside</th>
                        <th>Fee /<br>Discount</th>
                        <th>Ongkir</th>
                        <th>Total</th>
                        <th class="text-center">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach ($report as $key => $data)
                            <td>{{$key +1}}</td>
                            <td>{{$data->cust->name}}</td>
                            <td>{{$data->item->name}}</td>
                            <td>{{$data->item->no_seri}}</td>
                            <td>{{$data->access}}</td>
                            <td>
                                {{\Carbon\Carbon::parse($data->date_start)->translatedFormat('d F Y')}}
                            </td>
                            <td>
                                {{\Carbon\Carbon::parse($data->date_end)->translatedFormat('d F Y')}}
                            </td>
                            <td>{{formatRupiah($data->nominal_in)}}</td>
                            <td>{{formatRupiah($data->nominal_out)}}</td>
                            <td>{{formatRupiah($data->diskon)}}</td>
                            <td>{{formatRupiah($data->ongkir)}}</td>
                            <td>{{formatRupiah($data->total)}}</td>
                            <td class="text-center">
                                @if($data->status == 1)
                                    <span class="badge bg-success">Rental</span>
                                @elseif($data->status == 0)
                                    <span class="badge bg-secondary">Finished</span>
                                @elseif($data->status == 2)
                                    <span class="badge bg-danger">Problem</span>
                                @endif
                            </td>
                    </tr>
                    @endforeach
                    </tbody>
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
