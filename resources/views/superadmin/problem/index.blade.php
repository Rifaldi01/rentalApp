@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-6">
                    <div class="container mt-3">
                        <h4 class="text-uppercase">List Problem</h4>
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
                        <th>Accessories</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" width="18%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach($rental as $key => $data)
                            <td>{{$key +1}}</td>
                            <td>{{$data->rental->cust->name}}</td>
                            <td>{{$data->rental->item->name}}</td>
                            <td>{{$data->access}}
                            <td>
                                {{formatId($data->date_start)}}
                            </td>
                            <td>
                                {{formatId($data->date_end)}}
                            </td>
                            <td class="text-center">
                                    <span class="badge bg-danger">Problem</span>
                            </td>
                            <td>
                                <button class="btn btn-success lni lni-checkmark float-end" data-bs-toggle="modal"
                                        data-bs-placement="top" title="Finished"
                                        data-bs-target="#exampleVerticallycenteredModal{{$data->id}}"></button>
                                <div class="modal fade" id="exampleVerticallycenteredModal{{$data->id}}"
                                     tabindex="-1"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Pelunasan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('problem.finis', $data->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <label class="col-form-label">Nominal In</label>
                                                    <input type="number"
                                                           class="form-control" name="nominal_in"
                                                           value="{{$data->rental->nominal_in}}">
                                                    <label class="col-form-label">Nominal Outsid</label>
                                                    <input type="number"
                                                           class="form-control" name="nominal_out"
                                                           value="{{$data->rental->nominal_out}}">
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
                                        class="btn btn-dnd lni lni-eye float-end me-1" title="Detail">
                                </button>
                                <div class="modal fade" id="exampleLargeModal{{$data->id}}" tabindex="-1"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Problem</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <table id="" class="table table-bordered">
                                                        <tr>
                                                            <th width="5%"><div class="float-start">Name</div></th>
                                                            <td><div class="float-start">{{$data->rental->cust->name}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">Item</div></th>
                                                            <td><div class="float-start">{{$data->rental->item->name}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">Accessories</div></th>
                                                            <td><div class="float-start">{{$data->access}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">Start Date</div></th>
                                                            <td><div class="float-start">{{dateId($data->date_start)}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">End date</div></th>
                                                            <td><div class="float-start">{{dateId($data->date_end)}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">Descript</div></th>
                                                            <td>
                                                                <p class="text-danger">{{$data->descript}}</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <a href="https://api.whatsapp.com/send?phone=62{{$data->rental->cust->phone}}&text=Halo%20Customer%20yth,%20ada%20masalah%20dalam%20peminjaman%20anda%20segera%20konfirmasi%20kepada%20kami%20untuk%20menyelesaikan%20masalah%20terebut%20Termiaksih%20Atas%20Perhatianya.&source=&data="
                                       class="btn btn-success lni lni-whatsapp float-end me-1"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="Chat Customer">
                                    </a>
                                @if ($data->rental->status != 0)
                                    <form action="{{ route('problem.returned', $data->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning lni lni-package float-end me-1"
                                                onclick="return confirm('Item Returned?')" title="Item Returned">
                                        </button>
                                    </form>
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
