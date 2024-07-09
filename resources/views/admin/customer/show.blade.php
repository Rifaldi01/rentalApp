@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-head">
            <div class="row mt-2">
                <div class="col-6">
                    <div class="container">
                    <h4 class=" text-uppercase">{{$customer->name}} <i class="bx bx-history"></i></h4>
                    </div>
                </div>
                <div class="col-6">
                    <a href="{{route('admin.customer.index')}}" class="btn btn-warning float-end me-3 shadow">Back</a>
                </div>
            </div>
            <hr>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th>Item</th>
                        <th>Accessories</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="text-center">Total Day</th>
                        <th class="text-center">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rental as $key => $data)
                    <tr>
                        <td>{{$key +1}}</td>
                        <td>{{$data->item->name}}</td>
                        <td>{{$data->access}}</td>
                        <td>
                            {{formatId($data->date_start)}}
                        </td>
                        <td>
                            {{formatId($data->date_end)}}
                        </td>
                        <td class="text-center" width="10%">{{$data->days_difference}}</td>
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
                </table>
            </div>
        </div>
    </div>
@endsection

@push('head')

@endpush
@push('js')

@endpush
