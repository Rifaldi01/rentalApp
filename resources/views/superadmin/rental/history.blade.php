@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-6">
                    <div class="container mt-3">
                        <h4 class="text-uppercase">List History</h4>
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
                        <th>Item</th>
                        <th>No Seri</th>
                        <th>Accessories</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" width="14%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach($rental as $key => $data)
                            <td>{{$key +1}}</td>
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
                            <td> @php
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
                            <td>@if($data->access)
                                @foreach(explode(',', $data->access) as $accessory)
                                    <li>{{ $accessory }}</li>
                                @endforeach
                            @else
                                <li>No accessories</li>
                            @endif</td>
                            <td>
                                {{formatId($data->date_start)}}
                            </td>
                            <td>
                                {{formatId($data->date_end)}}
                            </td>
                            <td class="text-center">
                                @if($data->status == 1)
                                    <span class="badge bg-success">Rental</span>
                                @elseif($data->status == 0)
                                    <span class="badge bg-secondary">Finished</span>
                                @elseif($data->status == 2)
                                    <span class="badge bg-danger">Problem</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button data-bs-toggle="modal" data-bs-target="#exampleLargeModal{{$data->id}}"
                                        class="btn btn-dnd btn-sm lni lni-eye" title="view">
                                </button>
                                <div class="modal fade" id="exampleLargeModal{{$data->id}}" tabindex="-1"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail History</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <table id="" class="table table-bordered">
                                                        <tr>
                                                            <th width="5%"><div class="float-start">Name</div></th>
                                                            <td><div class="float-start">{{$data->cust->name}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">Item</div></th>
                                                            <td><div class="float-start">
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
                                                            </div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">Start Date</div></th>
                                                            <td><div class="float-start"> {{dateId($data->date_start)}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">End date</div></th>
                                                            <td><div class="float-start"> {{dateId($data->date_end)}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">Phone</div></th>
                                                            <td><div class="float-start">(+62){{$data->cust->phone}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">Nominal In</div></th>
                                                            <td><div class="float-start">{{formatRupiah($data->nominal_in)}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">Nominal outsid</div></th>
                                                            <td><div class="float-start">{{formatRupiah($data->nominal_out)}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">Discount</div></th>
                                                            <td><div class="float-start">{{formatRupiah($data->diskon)}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">Ongkir</div></th>
                                                            <td><div class="float-start">{{formatRupiah($data->ongkir)}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">Ket. Pembayar</div></th>
                                                            <td><div class="float-start">{{formatRupiah($data->date_pay)}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">Status</div></th>
                                                            <td>
                                                                @if($data->status == 0)
                                                                    <span class="badge bg-secondary float-start">Finished</span>
                                                                @elseif($data->status == 1)
                                                                    <span class="badge bg-success float-start">Rental</span>
                                                                @else
                                                                    <span class="badge bg-danger float-start">Problem</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="table-responsive">
                                                    <table id="" class="table table-bordered">
                                                        <tr>
                                                            <th colspan="2" class="bg-warning">Accessories</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Qty</th>
                                                        </tr>
                                                        @foreach($data->accessoriescategory as $asdf)
                                                        <tr>
                                                            <td>{{ $asdf->accessory->name }}</td>
                                                            <td>{{$asdf->accessories_quantity}}</td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                                <div class="table-responsive">
                                                    <table id="" class="table table-bordered">
                                                        <tr>
                                                            <th colspan="2" class="bg-primary">Data Company</th>
                                                        </tr>
                                                        <tr>
                                                            <th width="10%"><div class="float-start">Name Company</div></th>
                                                            <td><div class="float-start">{{$data->name_company}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">Adderess Company</div></th>
                                                            <td><div class="float-start">{{$data->addres_company}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">Phone Company</div></th>
                                                            <td><div class="float-start">{{$data->phone_company}}</div></td>
                                                        </tr>
                                                        <tr>
                                                            <th><div class="float-start">No PO</div></th>
                                                            <td><div class="float-start">{{$data->no_po}}</div></td>
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
