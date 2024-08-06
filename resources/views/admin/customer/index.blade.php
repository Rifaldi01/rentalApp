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
                                    <button data-bs-toggle="modal" data-bs-target="#exampleModal{{$data->id}}"
                                            class="btn btn-dnd btn-sm lni lni-eye" title="view">
                                    </button>
                                    <div class="modal fade" id="exampleModal{{$data->id}}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Images</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @php
                                                        $images = json_decode($data->image);
                                                    @endphp
                                                    @if($images && count($images) > 0)
                                                        <div class="d-flex flex-wrap">
                                                            @foreach($images as $image)
                                                                <div class="p-2">
                                                                    <img src="{{ asset('images/identity/'. $image) }}" alt="" class="img-fluid img-thumbnail">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-danger">Images Not Found!</span>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="{{ route('admin.customer.downloadImages', $data->id) }}" class="btn btn-info px-5">
                                                        <i class="bx bx-cloud-download"></i> Download All
                                                    </a>
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
