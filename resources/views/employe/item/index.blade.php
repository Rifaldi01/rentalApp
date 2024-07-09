@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-6">
                    <div class="container mt-3">
                        <h4 class="text-uppercase">List Items</h4>
                    </div>
                </div>
            </div>
        </div>
        <di class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th>Name</th>
                        <th>No Seri</th>
                        <th class="text-center">Image</th>
                        <th class="text-center">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach($item as $key => $data)
                            <td>{{$key +1}}</td>
                            <td>
                               {{$data->name}}
                            </td>
                            <td>{{$data->cat->name}}-{{$data->no_seri}}</td>
                            <td class="text-center">
                                @if($data->image)
                                    <button data-bs-toggle="modal" data-bs-target="#exampleModal{{$data->id}}"
                                            class="btn btn-dnd btn-sm lni lni-eye" title="view">
                                    </button>
                                    <div class="modal fade" id="exampleModal{{$data->id}}" tabindex="-1"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Image</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="{{asset('images/item/'.$data->image)}}" alt="">
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="{{asset('images/iten/'.$data->image)}}"
                                                       class="btn btn-info px-5" download>
                                                        <i class="bx bx-cloud-download"></i>Download</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-danger">Image Not Found!</span>
                                @endif

                            </td>
                            <td class="text-center">
                                @if($data->status == 2)
                                    <span class="badge bg-secondary">Rental</span>
                                @elseif($data->status == 0)
                                    <span class="badge bg-success">Redy</span>
                                @elseif($data->status == 1)
                                    <span class="badge bg-danger">Maintenance</span>
                                @endif
                            </td>
                    </tr>
                    @endforeach
                    </tbody>
                    </tfoot>
                </table>
            </div>
        </di>
    </div>
    </div>
@endsection

@push('head')

@endpush
@push('js')

@endpush
