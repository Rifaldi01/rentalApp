@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-6">
                    <div class="container mt-3">
                        <h4 class="text-uppercase">List Customer</h4>
                    </div>
                </div>
                <div class="col-6">
                    <div class="container mt-3">
                        <a href="{{route('admin.customer.create')}}" class="btn btn-dnd float-end me-3 btn-sm">
                            <i class="bx bx-plus"></i>
                        </a>
                        <button type="button" class="btn btn-success btn-sm float-end me-1" data-bs-toggle="modal"
                                data-bs-target="#exampleModal" data-bs-tool="tooltip"
                                data-bs-placement="top" title="Import Data Excel"><i class="bx bx-file"></i>
                        </button>
                    </div>
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
                        <th>No Identity</th>
                        <th>WhatsApp</th>
                        <th>Phone</th>
                        <th>address</th>
                        <th>Point Rental</th>
                        <th>Point Service</th>
                        <th class="text-center" width="4%">Identity</th>
                        <th class="text-center" width="9%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach($customer as $key => $data)
                            <td>{{$key +1}}</td>
                            <td>
                                <a href="{{route('admin.customer.show', $data->id)}}" class="text-dark">
                                    {{$data->name}}
                                </a>
                            </td>
                            <td>{{$data->no_identity}}</td>
                            <td>{{$data->phone}}</td>
                            <td class="text-center">
                                @if($data->phn)
                                    {{$data->phn}}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{Str::limit($data->addres, 26, '...')}}</td>
                            <td class="text-center">
                                @if($data->point_rental)
                                    {{$data->point_rental}}
                                @else
                                    0
                                @endif
                            </td>
                            <td class="text-center">
                                @if($data->point_service)
                                    {{$data->point_service}}
                                @else
                                    0
                                @endif
                            </td>
                            <td class="text-center">
                                <button data-bs-toggle="modal" data-bs-target="#exampleExtraLargeModal{{$data->id}}"
                                        class="btn btn-dnd btn-sm lni lni-eye" title="view">
                                </button>
                                <div class="modal fade" id="exampleExtraLargeModal{{$data->id}}" tabindex="-1"
                                     aria-labelledby="exampleExtraLargeModal" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Image</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="{{asset('images/identity/'.$data->image)}}" style="width:100%"
                                                     alt="">
                                                <table class="mt-3 bg-secondary">
                                                    <tr>
                                                        <th class="text-white"><p>Address : </p></th>
                                                        <td class="text-white"><p>{{$data->addres}}</p></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{asset('images/identity/'.$data->image)}}"
                                                   class="btn btn-info px-5" download>
                                                    <i class="bx bx-cloud-download"></i>Download Image</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{route('admin.customer.destroy', $data->id)}}" data-confirm-delete="true"
                                   type="submit" class=" bx bx-trash btn btn-sm btn-danger"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top" title="Hapus">
                                </a>
                                <a href="{{route('admin.customer.edit', $data->id)}}"
                                   class="btn btn-sm btn-warning bx bx-edit "
                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                </a>
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
