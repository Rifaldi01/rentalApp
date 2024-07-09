@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="col">
                <div class="row">
                    <div class="col-sm">
                        <h4 class="mb-0 text-uppercase">
                            Problem Report
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
                <form action="{{route('manager.problem.filter')}}" method="GET">
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
                        <th>Name </th>
                        <th>Item</th>
                        <th>No Seri</th>
                        <th>Accessories</th>
                        <th>Problem Date</th>
                        <th>Descript</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach ($report as $key => $data)
                            <td>{{$key +1}}</td>
                            <td>{{$data->rental->cust->name}}</td>
                            <td>{{$data->rental->item->name}}</td>
                            <td>{{$data->rental->item->cat->name}}-{{$data->rental->item->no_seri}}</td>
                            <td>{{$data->access}}</td>
                            <td>
                                {{\Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y')}}
                            </td>
                            <td>
                                {{$data->descript}}
                            </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('head')

@endpush

@push('js')

@endpush
