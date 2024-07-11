@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="col">
                <div class="row">
                    <div class="col-sm">
                        <h4 class="mb-0 text-uppercase">
                            Maintenance Report
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
                <form action="{{route('admin.mainten.filter')}}" method="GET">
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
                        <th>No Seri</th>
                        <th>Descript</th>
                        <th>Date Maintenance</th>
                        <th class="text-center">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach ($report as $key => $data)
                            <td>{{$key +1}}</td>
                            <td>{{$data->item->name}}</td>
                            <td>{{$data->item->cat->name}}-{{$data->item->no_seri}}</td>
                            <td>{{$data->descript}}</td>
                            <td>
                                {{\Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y')}}
                            </td>
                            <td class="text-center">
                                @if($data->status == 2)
                                    <span class="badge bg-secondary">Finished</span>
                                @elseif($data->status == 1)
                                    <span class="badge bg-success">Finished</span>
                                @elseif($data->status == 0)
                                    <span class="badge bg-danger">Maintenance</span>
                                @endif
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
