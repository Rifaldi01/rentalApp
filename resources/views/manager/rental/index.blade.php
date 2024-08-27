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
                       href="{{route('manager.rental.create')}}"
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
                            <td class="text-center">
                                <a href="{{route('manager.rental.edit', $data->id)}}"
                                   class="btn btn-warning lni lni-pencil me-1"
                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                </a>
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
