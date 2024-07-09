@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-6">
                    <div class="container mt-3">
                        <h4 class="text-uppercase">List Items Miantenance</h4>
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
                        <th class="text-center">Detail</th>
                        <th class="text-center" width="13%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach($mainten as $key => $data)
                            <td>{{$key +1}}</td>
                            <td>{{$data->item->name}}</td>
                            <td>{{$data->item->cat->name}}-{{$data->item->no_seri}}</td>
                            <td class="text-center">
                                <button data-bs-toggle="modal" data-bs-target="#exampleModal{{$data->id}}"
                                        class="btn btn-dnd btn-sm lni lni-eye" title="Detail">
                                </button>
                                <div class="modal fade" id="exampleModal{{$data->id}}" tabindex="-1"
                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Detail Maintenance</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <table id="" class="table table-bordered">
                                                <tr>
                                                    <th width="5%"><div class="float-start">Name</div></th>
                                                    <td><div class="float-start">{{$data->item->name}}</div></td>
                                                </tr>
                                                <tr>
                                                    <th width="5%"><div class="float-start">No Seri</div></th>
                                                    <td><div class="float-start">{{$data->item->cat->name}}-{{$data->item->no_seri}}</div></td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <div class="float-start">Descript</div>
                                                    </th>
                                                    <td>
                                                        <div class="float-start">{{$data->descript}}</div>
                                                    </td>
                                                </tr>
                                            </table>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.mainten.item', $data->id) }}" method="POST">
                                    @csrf
                                    <button onclick="return confirm('Maintenance Finis?');" type="submit" class="me-1 btn btn-success btn-sm bx bx-check" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Finished"></button>
                                </form>
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
