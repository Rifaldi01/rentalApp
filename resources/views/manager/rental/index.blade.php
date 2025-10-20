@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-6">
                    <div class="container mt-3">
                        <h4 class="text-uppercase">List Rental Aktif</h4>
                    </div>
                </div>
                <div class="col-6">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Add rental"
                       href="{{route('manager.rental.create')}}"
                       class="btn btn-dnd bx bx-plus float-end me-3 mt-3 shadow">
                    </a>

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
                        <th>Total <br>Inv</th>
                        <th width="">Ung <br>Masuk</th>
                        <th>Sisa <br>Bayar</th>
                        <th>Fee /<br>Discount</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="text-center">Total Day</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" width="8%">Print</th>
                        <th class="text-center" width="10%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach($rentals as $key => $data)
                            @if($data->status == 1)
                                <td>{{$key +1}}</td>
                                <td>{{$data->no_inv}}</td>
                                <td>{{$data->cust->name}}</td>
                                <td>
                                    @if($data->total_invoice)
                                        {{formatRupiah($data->total_invoice)}}
                                    @else
                                        Rp. 0
                                    @endif
                                </td>
                                <td>{{formatRupiah($data->nominal_in)}}</td>
                                <td>{{formatRupiah($data->nominal_out)}}</td>
                                <td>{{formatRupiah($data->diskon)}}</td>
                                <td>
                                    {{formatId($data->date_start)}}
                                </td>
                                <td>
                                    {{formatId($data->date_end)}}
                                </td>
                                <td class="text-center">{{$data->days_difference}} Day</td>
                                <td class="text-center">
                                    @if($data->status == 1)
                                        <span class="badge bg-success">Rent</span>
                                    @elseif($data->status == 0)
                                        <span class="badge bg-secondary">Finished</span>
                                    @elseif($data->status == 2)
                                        <span class="badge bg-danger">Problem</span>
                                    @endif
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
                                <td>
                                    <button data-bs-toggle="modal"
                                            data-bs-target="#exampleVerticallycenteredModal{{$data->id}}"
                                            class="btn btn-danger btn-sm lni lni-warning mt-1"
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
                                                <form action="{{route('manager.problem.store')}}"
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
                                       class="btn-sm btn btn-warning lni lni-pencil mt-1 me-1"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                    </a>
                                    <a href="https://api.whatsapp.com/send?phone=62{{$data->cust->phone}}&text=Halo%20Customer%20yth,%20masa%20tenggang%20peminjaman%20barang%20anda%20tersisa%20 3 %20Hari%20segera%20konfirmasi%20peminjaman%20anda%20Termiaksih%20Atas%20Perhatianya.&source=&data="
                                       class="btn-sm btn btn-success lni lni-whatsapp me-1 mt-1"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="Chat Customer">
                                    </a>

                                    <form action="{{ route('manager.rental.finis', $data->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="btn-sm btn btn-success lni lni-checkmark  mt-1"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Finished">

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
{{--    <div class="card">--}}
{{--        <div class="card-head">--}}
{{--            <div class="row">--}}
{{--                <div class="col-6">--}}
{{--                    <div class="container mt-3">--}}
{{--                        <h4 class="text-uppercase">List Rental</h4>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-6">--}}
{{--                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Add rental"--}}
{{--                       href="{{route('manager.rental.create')}}"--}}
{{--                       class="btn btn-dnd bx bx-plus float-end me-3 mt-3 shadow">--}}
{{--                    </a>--}}

{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="card-body">--}}
{{--            <div class="table-responsive">--}}
{{--                <table id="list2" class="table table-striped table-bordered fs-7 small-text" style="width:100%">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th width="2%" class="text-center">No</th>--}}
{{--                        <th>No Invoice</th>--}}
{{--                        <th>Name</th>--}}
{{--                        <th>Total <br>Inv</th>--}}
{{--                        <th width="">Ung <br>Masuk</th>--}}
{{--                        <th>Sisa <br>Bayar</th>--}}
{{--                        <th>Fee /<br>Discount</th>--}}
{{--                        <th>Start Date</th>--}}
{{--                        <th>End Date</th>--}}
{{--                        <th class="text-center">Total Day</th>--}}
{{--                        <th class="text-center">Status</th>--}}
{{--                        <th class="text-center" width="7%">Print</th>--}}
{{--                        <th class="text-center" width="10%">Action</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    <tr>--}}
{{--                        @foreach($rental as $key => $data)--}}
{{--                            <td>{{$key +1}}</td>--}}
{{--                            <td>{{$data->no_inv}}</td>--}}
{{--                            <td>{{$data->cust->name}}</td>--}}
{{--                            <td>--}}
{{--                                @if($data->total_invoice)--}}
{{--                                    {{formatRupiah($data->total_invoice)}}--}}
{{--                                @else--}}
{{--                                    Rp. 0--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                            <td>{{formatRupiah($data->nominal_in)}}</td>--}}
{{--                            <td>{{formatRupiah($data->nominal_out)}}</td>--}}
{{--                            <td>{{formatRupiah($data->diskon)}}</td>--}}
{{--                            <td>--}}
{{--                                {{formatId($data->date_start)}}--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                {{formatId($data->date_end)}}--}}
{{--                            </td>--}}
{{--                            <td class="text-center">{{$data->days_difference}} Day</td>--}}
{{--                            <td class="text-center">--}}
{{--                                @if($data->status == 1)--}}
{{--                                    <span class="badge bg-success">Rent</span>--}}
{{--                                @elseif($data->status == 0)--}}
{{--                                    <span class="badge bg-secondary">Finished</span>--}}
{{--                                @elseif($data->status == 2)--}}
{{--                                    <span class="badge bg-danger">Problem</span>--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                <button class="btn btn-dnd lni lni-files btn-sm" data-bs-toggle="modal"--}}
{{--                                        data-bs-target="#exampleExtraLargeModal{{$data->id}}" data-bs-tool="tooltip"--}}
{{--                                        data-bs-placement="top" title="Print Surat Jalan">--}}
{{--                                </button>--}}
{{--                                @include('manager.rental.surat-jalan')--}}
{{--                                <button type="button" class="btn btn-primary lni lni-empty-file btn-sm"--}}
{{--                                        data-bs-toggle="modal" id="btn-print{{$data->id}}"--}}
{{--                                        data-bs-target="#exampleLargeModal{{$data->id}}" data-bs-tool="tooltip"--}}
{{--                                        data-bs-placement="top" title="Print Invoice">--}}
{{--                                </button>--}}
{{--                                @include('manager.rental.invoice')--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                <button data-bs-toggle="modal"--}}
{{--                                        data-bs-target="#exampleVerticallycenteredModal{{$data->id}}"--}}
{{--                                        class="btn btn-danger btn-sm lni lni-warning mt-1"--}}
{{--                                        data-bs-placement="top" title="Problem">--}}
{{--                                </button>--}}
{{--                                <div class="modal fade" id="exampleVerticallycenteredModal{{$data->id}}" tabindex="-1"--}}
{{--                                     aria-hidden="true">--}}
{{--                                    <div class="modal-dialog modal-dialog-centered">--}}
{{--                                        <div class="modal-content">--}}
{{--                                            <div class="modal-header">--}}
{{--                                                <h5 class="modal-title">Descrpti Proble</h5>--}}
{{--                                                <button type="button" class="btn-close" data-bs-dismiss="modal"--}}
{{--                                                        aria-label="Close"></button>--}}
{{--                                            </div>--}}
{{--                                            <form action="{{route('manager.problem.store')}}"--}}
{{--                                                  method="POST">--}}
{{--                                                @csrf--}}
{{--                                                <div class="modal-body">--}}
{{--                                                    <input value="{{$data->id}}" type="hidden" name="rental_id"--}}
{{--                                                           class="form-control">--}}
{{--                                                    <label class="col-form-label">Descript</label>--}}
{{--                                                    <textarea type="text" name="descript"--}}
{{--                                                              class="form-control"--}}
{{--                                                              placeholder="Enter Descript"></textarea>--}}
{{--                                                </div>--}}
{{--                                                <div class="modal-footer">--}}
{{--                                                    <button type="button" class="btn btn-secondary"--}}
{{--                                                            data-bs-dismiss="modal">Close--}}
{{--                                                    </button>--}}
{{--                                                    <button type="submit" class="btn btn-primary">Save<i--}}
{{--                                                            class="bx bx-save"></i></button>--}}
{{--                                                </div>--}}
{{--                                            </form>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <a href="{{route('manager.rental.edit', $data->id)}}"--}}
{{--                                   class="btn-sm btn btn-warning lni lni-pencil mt-1 me-1"--}}
{{--                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">--}}
{{--                                </a>--}}
{{--                                <a href="https://api.whatsapp.com/send?phone=62{{$data->cust->phone}}&text=Halo%20Customer%20yth,%20masa%20tenggang%20peminjaman%20barang%20anda%20tersisa%20 3 %20Hari%20segera%20konfirmasi%20peminjaman%20anda%20Termiaksih%20Atas%20Perhatianya.&source=&data="--}}
{{--                                   class="btn-sm btn btn-success lni lni-whatsapp me-1 mt-1"--}}
{{--                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Chat Customer">--}}
{{--                                </a>--}}

{{--                                <form action="{{ route('manager.rental.finis', $data->id) }}" method="POST">--}}
{{--                                    @csrf--}}
{{--                                    <button type="submit"--}}
{{--                                            class="btn-sm btn btn-success lni lni-checkmark  mt-1"--}}
{{--                                            data-bs-toggle="tooltip" data-bs-placement="top"--}}
{{--                                            title="Finished">--}}

{{--                                    </button>--}}
{{--                                </form>--}}

{{--                            </td>--}}
{{--                    </tr>--}}
{{--                    @endforeach--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .small-text {
        font-size: 0.9rem; /* Ukuran font lebih kecil */
    }
</style>
@push('head')

@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#list2').DataTable({
                lengthChange: true,

            });

            // Tambahkan tombol ekspor ke container
            table.on('order.dt search.dt', function () {
                let i = 1;
                table.cells(null, 0, {search: 'applied', order: 'applied'}).every(function (cell) {
                    this.data(i++);
                });
            }).draw();
        });
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

        document.addEventListener('DOMContentLoaded', function () {
            // Tambahkan event listener untuk tombol "Finish"
            document.querySelectorAll('form button[type="submit"]').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault(); // Mencegah form dikirimkan langsung

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
            });
        });
    </script>
@endpush
