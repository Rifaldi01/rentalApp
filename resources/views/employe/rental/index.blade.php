@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-6">
                    <div class="container mt-3">
                        <h4 class="text-uppercase">Konfirmasi Rental</h4>
                    </div>
                </div>
                <div class="col-6">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="aktif" class="table table-striped table-bordered fs-7 small-text" style="width:100%">
                    <thead>
                    <tr>
                        <th width="2%" class="text-center">No</th>
                        <th>No Invoice</th>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="text-center" width="3%">Print</th>
                        <th class="text-center" width="3%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach($rentals as $key => $data)
                            @if($data->status == 3)
                                <td>{{$key +1}}</td>
                                <td>{{$data->no_inv}}</td>
                                <td>{{$data->cust->name}}</td>
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
                                    @include('admin.rental.surat-jalan')
                                </td>
                                <td>
                                    <form action="{{ route('employe.rental.approve', $data->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="btn-sm btn btn-success lni lni-checkmark  mt-1"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Setuju">

                                        </button>
                                    </form>

                                </td>
                    </tr>
                    @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-6">
                    <div class="container mt-3">
                        <h4 class="text-uppercase">Barang Kembali</h4>
                    </div>
                </div>
                <div class="col-6">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="kembali" class="table table-striped table-bordered fs-7 small-text" style="width:100%">
                    <thead>
                    <tr>
                        <th width="2%" class="text-center">No</th>
                        <th>No Invoice</th>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="text-center" width="3%">Print</th>
                        <th class="text-center" width="3%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach($rentals as $key => $data)
                            @if($data->status == 4)
                                <td>{{$key +1}}</td>
                                <td>{{$data->no_inv}}</td>
                                <td>{{$data->cust->name}}</td>
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
                                    @include('admin.rental.surat-jalan')
                                </td>
                                <td>
                                    <form action="{{ route('employe.rental.kemabli', $data->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="btn-sm btn btn-success lni lni-checkmark  mt-1"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Setuju">

                                        </button>
                                    </form>

                                </td>
                    </tr>
                    @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('head')

@endpush
@push('js')
    <script>
        $(document).ready(function () {
            var table = $('#aktif').DataTable();

            // Mengurutkan ulang nomor saat tabel diurutkan atau difilter
            table.on('order.dt search.dt', function () {
                let i = 1;
                table.cells(null, 0, {search: 'applied', order: 'applied'}).every(function (cell) {
                    this.data(i++);
                });
            }).draw();
        });
    </script>
    <script>
        $(document).ready(function () {
            var table = $('#kembali').DataTable();

            // Mengurutkan ulang nomor saat tabel diurutkan atau difilter
            table.on('order.dt search.dt', function () {
                let i = 1;
                table.cells(null, 0, {search: 'applied', order: 'applied'}).every(function (cell) {
                    this.data(i++);
                });
            }).draw();
        });
    </script>
@endpush
