@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-6">
                    <div class="container mt-3">
                        <h4 class="text-uppercase">List Items Sale</h4>
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
                        <th class="text-center">Detail</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach($sale as $key => $data)
                            <td>{{$key +1}}</td>
                            <td>
                                {{$data->item->name}}
                            </td>
                            <td>{{$data->item->cat->name}}-{{$data->item->no_seri}}</td>
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
                                <div class="modal fade" id="exampleVerticallycenteredModal{{$data->id}}" tabindex="-1"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Descrpti Maintenance</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <form action="{{route('mainten.store')}}"
                                                  method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <input value="{{$data->id}}" type="hidden" name="item_id"
                                                           class="form-control">
                                                    <label class="col-form-label">Descript</label>
                                                    <textarea type="text" name="descript"
                                                              class="form-control"
                                                              placeholder="Enter Descript"></textarea>
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
                                <button data-bs-toggle="modal" data-bs-target="#exampleLargeModal{{$data->id}}"
                                        class="btn btn-dnd btn-sm lni lni-eye me-1" title="view">
                                </button>
                                <div class="modal fade" id="exampleLargeModal{{$data->id}}" tabindex="-1"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Descript Sale</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <table id="" class="table table-bordered">
                                                        <tr>
                                                            <th width="5%"><div class="float-start">Name</div></th>
                                                            <td><div class="float-start">{{$data->item->name}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">No Seri</div></th>
                                                            <td><div class="float-start">{{$data->item->no_seri}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">Descript</div></th>
                                                            <td><div class="float-start">{{($data->descript)}}</div></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning">Sale</span>
                            </td>
                            <td class="text-center">
                                    <a href="{{ route('manager.sale.destroy', $data->id) }}" data-confirm-delete="true"
                                       class="btn btn-danger btn-sm bx bx-trash" data-bs-toggle="tooltip"
                                       data-bs-placement="top" title="Delete">
                                    </a>
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
