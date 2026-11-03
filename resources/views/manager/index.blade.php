@extends('layouts.master')
@section('content')
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <a href="{{route('manager.rental.index')}}">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Rental Active</p>
                                <h4 class="my-1 text-info">{{$rental}}</h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i
                                    class='lni lni-timer'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{route('manager.rental.hsty')}}">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Rental History</p>
                                <h4 class="my-1 text-info">{{$history}}</h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i
                                    class='bx bx-history'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{route('rental.problems')}}">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Rental Problem</p>
                                <h4 class="my-1 text-info">{{$problem}}</h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i
                                    class='lni lni-warning'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{route('manager.customer.index')}}">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Customers</p>
                                <h4 class="my-1 text-warning">{{$customer}}</h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i
                                    class='bx bxs-group'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="card">
        <div class="container mt-2">
            <h5>Stok Item <i class="bx bx-box"></i></h5>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Items</p>
                            <h4 class="my-1 text-warning">{{$item}}</h4>
                            <p class="mb-0 font-13">All Items</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i
                                class='bx bxs-box'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @foreach ($itemsByCategory as $item)
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{$item->cat->name}}</p>
                                <h4 class="my-1 text-warning">{{ $item->available }} Available</h4>
                                <p class="mb-0 font-13">Of {{ $item->total }}</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i
                                    class='bx bx-box'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Item Maintenance</p>
                            <h4 class="my-1 text-success">{{$maintenance}}</h4>
                            <p class="mb-0 font-13">Chek Item Maintenance</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i
                                class='bx bx-box'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-2">
        <div class="container mt-2">
            <h4>Rentals that End in 3 Days. {{$jumlah}} Rental</h4>
        </div>
    </div>
    @if($rentals->isEmpty())
        <div class="card">
            <div class="card-body">
                <p>No rentals ending in 3 days.</p>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th width="2%">No</th>
                            <th>Customer</th>
                            <th>Iemt</th>
                            <th>No Seri</th>
                            <th>Accessories</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th class="text-center">Total Day</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" width="14%">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            @foreach($rentals as $key => $data)
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
                                    {{\Carbon\Carbon::parse($data->date_start)->translatedFormat('d F Y')}}
                                </td>
                                <td>
                                    {{\Carbon\Carbon::parse($data->date_end)->translatedFormat('d F Y')}}
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
                                    <button data-bs-toggle="modal"
                                            data-bs-target="#exampleVerticallycenteredModal{{$data->id}}"
                                            class="btn btn-danger float-end lni lni-warning "
                                            data-bs-placement="top" title="Edit">
                                    </button>
                                    <div class="modal fade" id="exampleVerticallycenteredModal{{$data->id}}"
                                         tabindex="-1"
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
                                    <a href="{{route('manager.rental.edit', $data->id)}}"
                                       class="btn btn-warning lni lni-pencil float-end me-1"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                    </a>
                                    <form action="{{ route('rental.finis', $data->id) }}" method="POST">
                                        @csrf
                                        <button onclick="return confirm('Rental Finished?');" type="submit"
                                                class="btn btn-success lni lni-checkmark float-end me-1"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Finished"></button>
                                    </form>
                                    <a href="https://api.whatsapp.com/send?phone=62{{$data->cust->phone}}&text=Halo%20Customer%20yth,%20masa%20tenggang%20peminjaman%20barang%20anda%20tersisa%20 3 %20Hari%20segera%20konfirmasi%20peminjaman%20anda%20Termiaksih%20Atas%20Perhatianya.&source=&data="
                                       class="btn btn-success lni lni-whatsapp float-end me-1 mt-2"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="Chat Customer">
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
    @endif

@endsection

@push('head')

@endpush
@push('js')

@endpush
