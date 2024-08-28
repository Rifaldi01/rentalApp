@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-12 mb-2">
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
                </div>
                <div class="col-6">
                    <div class="container mt-3">
                        <h4 class="text-uppercase">List Rental</h4>
                    </div>
                </div>
                <div class="col-6">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Add rental"
                       href="{{route('superadmin.rental.create')}}"
                       class="btn btn-dnd float-end me-3 mt-3 btn-sm shadow"><i
                            class="bx bx-plus"></i>
                    </a>
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
                        <th>Item</th>
                        <th>No Seri</th>
                        <th>Accessories</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="text-center">Total Day</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach($rental as $key => $data)
                        <td>{{$key +1}}</td>
                            <td>{{$data->cust->name}}</td>
                            <td>
                                @php
                                    $itemIds = json_decode($data->item_id);
                                @endphp
                                @if(is_array($itemIds))
                                    @foreach($itemIds as $itemId)
                                        @php
                                            $item = \App\Models\Item::find($itemId);
                                        @endphp
                                            <li>{{ $item ? $item->name : 'Item not found' }}</li>
                                    @endforeach
                                @else
                                    {{ $itemIds }}
                                @endif
                            </td>
                            <td>
                                @php
                                    $itemIds = json_decode($data->item_id);
                                @endphp
                                @if(is_array($itemIds))
                                    @foreach($itemIds as $itemId)
                                        @php
                                            $item = \App\Models\Item::find($itemId);
                                        @endphp
                                            <li>{{ $item ? $item->cat->name : null }}-{{ $item ? $item->no_seri : 'Item not found' }}</li>
                                    @endforeach
                                @else
                                    {{ $itemIds }}
                                @endif
                            </td>
                            <td>
                            @if($data->access)
                                @foreach(explode(',', $data->access) as $accessory)
                                    <li>{{ $accessory }}</li>
                                @endforeach
                            @else
                                <li>No accessories</li>
                            @endif
                            </td>
                            <td>
                                {{formatId($data->date_start)}}
                            </td>
                            <td>
                                {{formatId($data->date_end)}}
                            </td>
                            <td class="text-center">{{$data->days_difference}} Day</td>
                            <td class="text-center">
                                @if($data->status == 1)
                                    <span class="badge bg-success">Rental</span>
                                @elseif($data->status == 0)
                                    <span class="badge bg-secondary">Finished</span>
                                @elseif($data->status == 2)
                                    <span class="badge bg-danger">Problem</span>
                                @endif
                            </td>
                            <td>
                                @if($data->status == 0)
                                    <a href="{{route('superadmin.rental.edit', $data->id)}}"
                                       class="btn btn-warning lni lni-pencil float-end "
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                    </a>
                                    <form action="{{ route('rental.finis', $data->id) }}" method="POST">
                                        @csrf
                                        <button onclick="return confirm('Rental Finished?');" type="submit"
                                                class="btn btn-success lni lni-checkmark float-end "
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Finished"></button>
                                    </form>
                                @elseif($data->status == 1)
                                    <button data-bs-toggle="modal"
                                            data-bs-target="#exampleVerticallycenteredModal{{$data->id}}"
                                            class="btn btn-danger float-end lni lni-warning mt-1"
                                            data-bs-placement="top" title="Problem">
                                    </button>
                                    <div class="modal fade" id="exampleVerticallycenteredModal{{$data->id}}" tabindex="-1"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Descrpti Proble</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <form action="{{route('problem.store')}}"
                                                      method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <input value="{{$data->id}}" type="hidden" name="rental_id"
                                                               class="form-control" >
                                                        <label class="col-form-label">Descript</label>
                                                        <textarea  type="text" name="descript"
                                                                   class="form-control" placeholder="Enter Descript"></textarea>
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
                                    <a href="{{route('superadmin.rental.edit', $data->id)}}"
                                       class="btn btn-warning lni lni-pencil float-end mt-1 me-1"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                    </a>
                                    <form action="{{ route('rental.finis', $data->id) }}" method="POST">
                                        @csrf
                                        <button onclick="return confirm('Rental Finished?');" type="submit"
                                                class="btn btn-success lni lni-checkmark float-end mt-1"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Finished">

                                        </button>
                                    </form>
                                    <a href="https://api.whatsapp.com/send?phone=62{{$data->cust->phone}}&text=Halo%20Customer%20yth,%20masa%20tenggang%20peminjaman%20barang%20anda%20tersisa%20 3 %20Hari%20segera%20konfirmasi%20peminjaman%20anda%20Termiaksih%20Atas%20Perhatianya.&source=&data="
                                       class="btn btn-success lni lni-whatsapp float-end me-1 mt-1"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="Chat Customer">
                                    </a>
                                @elseif($data->status == 2)
                                    <a href="{{route('superadmin.rental.edit', $data->id)}}"
                                       class="btn btn-warning lni lni-eye float-end "
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="Detail">
                                    </a>
                                    <form action="{{ route('problem.finis', $data->id) }}" method="POST">
                                        @csrf
                                        <button onclick="return confirm('Rental Finished?');" type="submit"
                                                class="btn btn-success lni lni-checkmark float-end me-1"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Finished"></button>
                                    </form>
                                    <a href="https://api.whatsapp.com/send?phone=62{{$data->cust->phone}}&text=Halo%20Customer%20yth,%20ada%20masalah%20dalam%20peminjaman%20anda%20segera%20konfirmasi%20kepada%20kami%20untuk%20menyelesaikan%20masalah%20terebut%20Termiaksih%20Atas%20Perhatianya.&source=&data="
                                       class="btn btn-success lni lni-whatsapp float-end "
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="Chat Customer">
                                    </a>
                                @endif
                            </td>
                    </tr>
                    @endforeach
                    </tbody>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('head')

@endpush
@push('js')
@endpush
