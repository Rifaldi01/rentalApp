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
                <div class="col-6">
                    <div class="me-2">
                        <form method="GET" action="{{ route('marketing.rental') }}" class="float-end mt-3">
                            <div class="row">
                                <div class="col-sm-4">
                                    <select name="tahun" class="form-select">

                                        <option value="all"
                                            {{ request()->has('tahun') && request('tahun') == 'all' ? 'selected' : '' }}>
                                            Semua Tahun
                                        </option>

                                        @foreach($listTahun as $item)
                                            <option value="{{ $item->thn }}"
                                                {{
                                                    request()->has('tahun')
                                                        ? (request('tahun') == $item->thn ? 'selected' : '')
                                                        : (date('Y') == $item->thn ? 'selected' : '')
                                                }}>
                                                {{ $item->thn }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <select name="bulan" class="form-select">

                                        <option value="all"
                                            {{ request()->has('bulan') && request('bulan') == 'all' ? 'selected' : '' }}>
                                            Semua Bulan
                                        </option>

                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}"
                                                {{
                                                    request()->has('bulan')
                                                        ? (request('bulan') == $i ? 'selected' : '')
                                                        : (date('n') == $i ? 'selected' : '')
                                                }}>
                                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                            </option>
                                        @endfor

                                    </select>
                                </div>
                                <div class="col-sm-4" >
                                    <button class="btn btn-success">Filter</button>
                                    <a href="{{route('marketing.rental')}}" class="btn btn-danger">Reset</a>
                                </div>
                            </div>
                        </form>
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
                        <th>Tgl Invoice</th>
                        <th>Invoice</th>
                        <th>Name</th>
                        <th>Item</th>
                        <th>No Seri</th>
                        <th>Accessories</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Print</th>
                        <th class="text-center">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach($rentals as $key => $data)
                            <td>{{$key +1}}</td>
                            <td>{{formatId($data->tgl_inv)}}</td>
                            <td>{{$data->no_inv}}</td>
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
                                        <li>{{ $item ? $item->cat->name : null }}
                                            -{{ $item ? $item->no_seri : 'Item not found' }}</li>
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
                            <td>
                                <button class="btn btn-dnd lni lni-files btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#exampleExtraLargeModal{{$data->id}}" data-bs-tool="tooltip"
                                        data-bs-placement="top" title="Print Surat Jalan">
                                </button>
                                @include('manager.rental.surat-jalan')
                                <button class="btn btn-warning lni lni-files btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#penyewaan{{$data->id}}" data-bs-tool="tooltip"
                                        data-bs-placement="top" title="Print Surat Penyewa">
                                </button>
                                @include('admin.rental.penyewaan')
                                <button type="button" class="btn btn-primary lni lni-empty-file btn-sm"
                                        data-bs-toggle="modal" id="btn-print{{$data->id}}"
                                        data-bs-target="#exampleLargeModal{{$data->id}}" data-bs-tool="tooltip"
                                        data-bs-placement="top" title="Print Invoice">
                                </button>
                                @include('manager.rental.invoice')
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
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('head')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet"/>
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(".datepicker").flatpickr();
        $(".time-picker").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "Y-m-d H:i",
        });
        $(".date-time").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
        $(".date-format").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
        $(".date-range").flatpickr({
            mode: "range",
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
        $(".date-inline").flatpickr({
            inline: true,
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
    </script>
@endpush
