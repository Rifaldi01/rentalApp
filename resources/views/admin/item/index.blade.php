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
                <div class="col-6">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Add Item"
                       href="{{route('admin.item.create')}}" class="btn btn-dnd float-end me-3 mt-3 btn-sm shadow"><i
                            class="bx bx-plus"></i>
                    </a>
                </div>
            </div>
        </div>
        <di class="card-body">
            <div class="table-responsive">
                <table id="example3" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th>Name</th>
                        <th>No Seri</th>
                        <th class="text-center">Image</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" width="15%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach($item as $key => $data)
                            <td>{{$key +1}}</td>
                            <td>
                                <a href="{{route('admin.item.show', $data->id)}}" class="text-dark">{{$data->name}}</a>
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
                            <td>
                                <a href="{{route('admin.item.destroy', $data->id)}}" data-confirm-delete="true"
                                   class="btn btn-danger btn-sm bx bx-trash float-end" data-bs-toggle="tooltip"
                                   data-bs-placement="top" title="Delete">
                                </a>
                                <a href="{{route('admin.item.edit', $data->id)}}"
                                   class="btn btn-sm btn-dnd bx bx-edit float-end me-1"
                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                </a>
                                <button data-bs-toggle="modal"
                                        data-bs-target="#exampleVerticallycenteredModal{{$data->id}}"
                                        class="btn btn-success btn-sm float-end bx bx-shield-quarter me-1"
                                        data-bs-placement="top" title="maintenance">
                                </button>
                                <div class="modal fade" id="exampleVerticallycenteredModal{{$data->id}}" tabindex="-1"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Descrpti Maintenance</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <form action="{{route('admin.mainten.store')}}"
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
                                        class="btn btn-warning btn-sm bx bx-dollar float-end me-1" title="view">
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
                                                <form action="{{route('admin.item.sale')}}"
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
                                </div>
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
