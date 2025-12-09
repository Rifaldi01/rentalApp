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
                                    <button class="btn btn-warning lni lni-files btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#penyewaan{{$data->id}}" data-bs-tool="tooltip"
                                            data-bs-placement="top" title="Print Surat Penyewa">
                                    </button>
                                    @include('admin.rental.penyewaan')
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
                        <th class="text-center" width="7%">Action</th>
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
                                    <button class="btn btn-warning lni lni-package float-end me-1"
                                            data-bs-toggle="modal" data-bs-target="#kembali{{ $data->id }}"
                                            title="Returned">
                                    </button>

                                    <div class="modal fade" id="kembali{{ $data->id }}" tabindex="-1"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-center">Apakah Sudah Dikembalikan?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('employe.rental.kembali', $data->id) }}"
                                                      method="POST">
                                                    @csrf
                                                    <div class="container">
                                                        <div class="mt-2 mb-2">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                <tr>
                                                                    <td colspan="5">Note : @if($data->problems->isNotEmpty())
                                                                            @foreach($data->problems as $problem)
                                                                                {{ $problem->descript }}
                                                                            @endforeach
                                                                        @else
                                                                            <span class="text-muted">Tidak ada keterangan</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-center" width="1%">No</th>
                                                                    <th class="text-center">Nama Barang</th>
                                                                    <th class="text-center">Belum Kembali</th>
                                                                    <th class="text-center" width="10%">Barang Kembali
                                                                    </th>
                                                                    <th class="text-center">No Seri</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @php
                                                                    $itemIds = json_decode($data->item_id);
                                                                    $no = 1;
                                                                @endphp

                                                                {{-- Barang utama --}}
                                                                @if(is_array($itemIds))
                                                                    @foreach($itemIds as $index => $itemId)
                                                                        @php
                                                                            $item = \App\Models\Item::find($itemId);
                                                                        @endphp
                                                                        <tr>
                                                                            <td class="text-center">{{ $no++ }}</td>
                                                                            <td>{{ $item ? $item->name : 'Item not found' }}</td>
                                                                            <td class="text-center">-</td>
                                                                            <td class="text-center">
                                                                                @if($item && $item->status == 2)
                                                                                    {{-- Jika status masih 1 (belum selesai), tampilkan checkbox --}}
                                                                                    <input type="hidden"
                                                                                           name="items[{{ $index }}][id]"
                                                                                           value="{{ $item->id }}">
                                                                                    <input type="checkbox"
                                                                                           name="items[{{ $index }}][status]"
                                                                                           value="0">
                                                                                @else
                                                                                    {{-- Jika status bukan 1, tampilkan badge selesai --}}
                                                                                    <span class="badge bg-secondary">Selesai</span>
                                                                                @endif
                                                                            </td>

                                                                            <td>{{ $item ? $item->no_seri : 'Item not found' }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif

                                                                {{-- Accessories --}}
                                                                @foreach($data->accessoriescategory as $i => $acces)
                                                                    <tr>
                                                                        <td class="text-center">{{ $no++ }}</td>
                                                                        <td>{{ $acces->accessory ? $acces->accessory->name : 'Not Found' }}</td>
                                                                        <td class="text-center">{{ $acces->accessories_quantity }}</td>
                                                                        <td class="text-center">
                                                                            @if($acces->accessories_quantity != 0)
                                                                                <input type="hidden"
                                                                                       name="accessories[{{ $i }}][id]"
                                                                                       value="{{ $acces->id }}">
                                                                                <input type="number"
                                                                                       name="accessories[{{ $i }}][kembali]"
                                                                                       class="form-control" min="0"
                                                                                       value="0">
                                                                            @else
                                                                                <span
                                                                                    class="badge bg-secondary">Selesai</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>Aksesoris</td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger"
                                                                data-bs-dismiss="modal">Batal
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">Ya</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <form action="{{ route('employe.rental.finis', $data->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" id="btnFinish"
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

        document.addEventListener('DOMContentLoaded', function () {
            // Ambil tombol dengan ID tertentu
            const finishButton = document.getElementById('btnFinish');

            if (finishButton) {
                finishButton.addEventListener('click', function (event) {
                    event.preventDefault(); // Mencegah form langsung terkirim

                    const form = this.closest('form'); // Ambil form terdekat

                    Swal.fire({
                        title: 'Konfirmasi',
                        text: "Apakah Anda yakin ingin menyelesaikan rental ini?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, selesai!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Kirimkan form jika tombol "Ya" diklik
                        }
                    });
                });
            }
        });

    </script>
@endpush
