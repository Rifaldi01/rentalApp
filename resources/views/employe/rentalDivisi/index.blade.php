@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-6">
                    <div class="container mt-3">
                        <h4 class="text-uppercase">Pinjaman Divisi</h4>
                    </div>
                </div>
                <div class="col-6">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Add rental"
                       href="{{route('employe.rentaldivisi.create')}}"
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
                        <th>Kode Pinjaman</th>
                        <th>Nama Divisi</th>
                        <th>Deskripsi</th>
                        <th>Tanggal Peminjaman</th>
                        <th>Status</th>
                        <th>Print</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach($rentalDivisi as $key => $data)
                                <td>{{$key +1}}</td>
                                <td>{{$data->kode_pinjaman}}</td>
                                <td>{{$data->divisi->name}}</td>
                                <td>
                                    {{$data->description}}
                                </td>
                                <td>{{formatId($data->created_at)}}</td>
                                <td class="text-center">
                                    @if($data->status == 1)
                                        <span class="badge bg-success">Finished</span>
                                    @elseif($data->status == 0)
                                        <span class="badge bg-secondary">Dipinjam</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-dnd lni lni-files btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#exampleExtraLargeModal{{$data->id}}" data-bs-tool="tooltip"
                                            data-bs-placement="top" title="Print Surat Jalan">
                                    </button>
                                    @include('employe.rentalDivisi.surat-jalan')
                                </td>
                                <td>
                                    @if($data->status == 1)
                                            <button type="button" id="finis"
                                                    class="btn-sm btn btn-success lni lni-checkmark  mt-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Finished" disabled>

                                            </button>
                                    @elseif($data->status == 0)
                                        <form action="{{ route('employe.rentaldivisi.finis', $data->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="btn-sm btn btn-success lni lni-checkmark  mt-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Finished">

                                            </button>
                                        </form>
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
        document.addEventListener('DOMContentLoaded', function () {
            // Menonaktifkan tombol dengan id "finis"
            const button = document.getElementById('finis');
            if (button) {
                button.disabled = true;
                button.classList.add('disabled');
                button.style.opacity = '0.6';
                button.title = 'Tombol dinonaktifkan otomatis';
            }
        });
    </script>
@endpush
