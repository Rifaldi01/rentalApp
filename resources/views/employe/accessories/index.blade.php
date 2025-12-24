@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-6">
                    <div class="container mt-3">
                        <h4 class="text-uppercase">List accesories</h4>
                    </div>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-dnd float-end me-3 mt-3 btn-sm shadow" data-bs-toggle="modal"
                            data-bs-target="#exampleVerticallycenteredModal"><i class="bx bx-plus"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="accessories" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th>Name</th>
                        <th>Stok All</th>
                        <th>Stok Available</th>
                        <th>Rental</th>
                        <th>Rental Divisi</th>
                        <th>Maintenance</th>
                        <th class="text-center" width="9%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($accessoriesData as $key => $data)
                        <tr>
                            <td data-index="{{ $key + 1 }}">{{ $key + 1 }}</td>
                            <td>{{ $data['name'] }}</td>
                            <td>{{ $data['stok_all'] }}</td>
                            <td>{{ $data['stok'] }}</td>
                            <td>{{ $data['rentedQty'] }}</td>
                            <td>{{ $data['borrowedQty'] }}</td>
                            <td>{{ $data['maintenanceQty'] }}</td>
                            <td>
                                <form action="{{ route('employe.acces.destroy', $data['id']) }}"
                                      method="POST"
                                      class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button"
                                            class="btn btn-danger btn-sm bx bx-trash btn-delete"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Delete">
                                    </button>
                                </form>


                                <button data-bs-toggle="modal"
                                        data-bs-target="#exampleVerticallycenteredModal{{ $data['id'] }}"
                                        class="btn btn-warning btn-sm float-end bx bx-edit ms-2" data-bs-placement="top"
                                        title="Edit"></button>
                                <div class="modal fade" id="exampleVerticallycenteredModal{{ $data['id'] }}"
                                     tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Accessories</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('employe.acces.update', $data['id']) }}"
                                                  method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <label class="col-form-label">Name Accessories</label>
                                                    <input value="{{ $data['name'] }}" type="text" name="name"
                                                           class="form-control" placeholder="Enter Accessories">
                                                    <label class="col-form-label mt-2">Stok All</label>
                                                    <input type="number" value="{{ $data['stok_all'] }}" name="stok_all"
                                                           class="form-control" placeholder="">
                                                    <label class="col-form-label mt-2">Stok Available</label>
                                                    <input type="number" value="{{ $data['stok'] }}" name="stok"
                                                           class="form-control" placeholder="">
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

                                <button data-bs-toggle="modal"
                                        data-bs-target="#tambahstok{{ $data['id'] }}"
                                        class="btn btn-dnd btn-sm float-end bx bx-edit ms-2" data-bs-placement="top"
                                        title="Edit"></button>
                                <div class="modal fade" id="tambahstok{{ $data['id'] }}"
                                     tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Accessories</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('employe.acces.tambah', $data['id']) }}"
                                                  method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <label class="col-form-label">Tanggal</label>
                                                    <input type="text" name="created_at" id="" class="form-control datepicker" placeholder="Masukan Tanggal">
                                                    <label class="col-form-label">Name Accessories</label>
                                                    <input value="{{ $data['name'] }}" type="text" name="name"
                                                           class="form-control" placeholder="Enter Accessories" readonly>
                                                    <label class="col-form-label mt-2">qty</label>
                                                    <input type="number" value="0" name="stok"
                                                           class="form-control" placeholder="">
                                                    <label class="col-form-label mt-2">Keterangan</label>
                                                    <textarea name="description" class="form-control" id="" cols="30" rows="5"></textarea>
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
                            </td>
                        </tr>
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
                        <h4 class="text-uppercase">Accessories Rental</h4>
                    </div>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-dnd float-end me-3 mt-3 btn-sm shadow" data-bs-toggle="modal"
                            data-bs-target="#exampleVerticallycenteredModal"><i class="bx bx-plus"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="accessoriesRental" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th>Inovice</th>
                        <th>Name</th>
                        <th>Customer</th>
                        <th>Qty</th>
                        <th>Periode</th>
                        <th>Keterangan</th>
                        {{--                        <th class="text-center" width="9%">Action</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rentals as $key => $data)
                        <tr>
                            <td data-index="{{ $key + 1 }}">{{ $key + 1 }}</td>
                            <td>
                                @if($data->rental_id)
                                    {{ $data->rental->no_inv ?? '-'}}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($data->accessories_id)
                                    {{ $data->accessory->name ?? '-'}}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($data->rental_id)
                                    {{ $data->rental->cust->name ?? '-'}}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                {{$data->accessories_quantity + $data->kembali}}
                            </td>
                            <td>
                                @if($data->rental_id && $data->rental)
                                    {{ $data->rental->date_start ? formatId($data->rental->date_start) : '-' }}
                                    -
                                    {{ $data->rental->date_end ? formatId($data->rental->date_end) : '-' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($data->status_acces == 1)
                                    <span class="badge bg-success">Rent</span>
                                @elseif($data->status_acces == 0)
                                    <span class="badge bg-secondary">Finished</span>
                                @endif
                            </td>
                            {{--                        <td>--}}
                            {{--                            <a href="{{ route('admin.acces.destroy', $data['id']) }}" data-confirm-delete="true" class="btn btn-danger btn-sm bx bx-trash" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"></a>--}}
                            {{--                            <button data-bs-toggle="modal" data-bs-target="#exampleVerticallycenteredModal{{ $data['id'] }}" class="btn btn-warning btn-sm float-end bx bx-edit ms-2" data-bs-placement="top" title="Edit"></button>--}}

                            {{--                            <!-- Edit Modal -->--}}
                            {{--                            <div class="modal fade" id="exampleVerticallycenteredModal{{ $data['id'] }}" tabindex="-1" aria-hidden="true">--}}
                            {{--                                <div class="modal-dialog modal-dialog-centered">--}}
                            {{--                                    <div class="modal-content">--}}
                            {{--                                        <div class="modal-header">--}}
                            {{--                                            <h5 class="modal-title">Edit Accessories</h5>--}}
                            {{--                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                            {{--                                        </div>--}}
                            {{--                                        <form action="{{ route('admin.acces.update', $data['id']) }}" method="POST">--}}
                            {{--                                            @csrf--}}
                            {{--                                            @method('PUT')--}}
                            {{--                                            <div class="modal-body">--}}
                            {{--                                                <label class="col-form-label">Name Accessories</label>--}}
                            {{--                                                <input value="{{ $data['name'] }}" type="text" name="name" class="form-control" placeholder="Enter Accessories">--}}
                            {{--                                                <label class="col-form-label mt-2">Stok</label>--}}
                            {{--                                                <input type="number" value="{{ $data['stok'] }}" name="stok" class="form-control" placeholder="">--}}
                            {{--                                            </div>--}}
                            {{--                                            <div class="modal-footer">--}}
                            {{--                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>--}}
                            {{--                                                <button type="submit" class="btn btn-primary">Save<i class="bx bx-save"></i></button>--}}
                            {{--                                            </div>--}}
                            {{--                                        </form>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                            <!-- End of Edit Modal -->--}}
                            {{--                        </td>--}}
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total Accessories dirental</th>
                        <th></th>
                        <th colspan="2"></th>
                    </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleVerticallycenteredModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Accessories</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('employe.acces.store')}}" method="POST" id="myForm">
                    @csrf
                    <div class="modal-body">
                        <label class="col-form-label">Tanggal</label>
                        <input type="text" name="created_at" id="" class="form-control datepicker" placeholder="Enter Accessories">
                        <label class="col-form-label">Name Accesories</label>
                        <input type="text" name="name" id="" class="form-control" placeholder="Enter Accessories">
                        <label class="col-form-label mt-2">Stok All</label>
                        <input type="number" name="stok_all" id="" class="form-control" placeholder="">
                        <label class="col-form-label mt-2">Stok Available</label>
                        <input type="number" name="stok" id="" class="form-control" placeholder="">
                        <label class="col-form-label mt-2">Keterangan</label>
                        <textarea name="description" class="form-control" id="" cols="10" rows="5"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Save<i class="bx bx-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('head')

@endpush
@push('js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        // Helper: inisialisasi single flatpickr jika belum ada
        function initFlatpickrOn(el, options = {}) {
            if (!el) return;
            // flatpickr menyimpan instance di property _flatpickr pada elemen DOM
            if (!el._flatpickr) {
                flatpickr(el, options);
            }
        }

        $(document).ready(function () {
            // 1) Inisialisasi datepicker untuk elemen yang sudah ada di DOM
            document.querySelectorAll('.datepicker').forEach(function(input) {
                initFlatpickrOn(input, {
                    // default options — sesuaikan bila perlu
                    dateFormat: "Y-m-d",
                    allowInput: true
                });
            });

            // 2) Pastikan datepicker di dalam modal di-inisialisasi saat modal muncul
            // Ini menangani modal yang mengandung input datepicker yang mungkin belum di-inisialisasi
            $(document).on('shown.bs.modal', '.modal', function () {
                // inisialisasi hanya untuk elemen datepicker di dalam modal yang sedang dibuka
                $(this).find('.datepicker').each(function () {
                    initFlatpickrOn(this, {
                        dateFormat: "Y-m-d",
                        allowInput: true
                    });
                });
            });

            // 3) Tombol submit di modal (hindari double submit)
            $(document).on('click', '#submitBtn', function (e) {
                var $btn = $(this);
                $btn.prop('disabled', true).text('Loading...');
                // Jika button ada di dalam form, submit form terdekat
                var $form = $btn.closest('form');
                if ($form.length) {
                    $form.submit();
                } else {
                    // fallback: submit form dengan id myForm jika ada
                    $('#myForm').submit();
                }
            });

            // 4) DataTables: init once per table (hindari inisialisasi ganda)
            if (! $.fn.DataTable.isDataTable('#accessories')) {
                var table1 = $('#accessories').DataTable({
                    lengthChange: false,
                    buttons: [{
                        extend: 'pdf',
                        exportOptions: { columns: [0,1,2,3,4] },
                        customize: function (doc) { doc.content[1].alignment = 'center'; }
                    }, {
                        extend: 'print',
                        exportOptions: { columns: [0,1,2,3,4] },
                        customize: function (win) { $(win.document.body).find('table').addClass('table-center'); }
                    }]
                });
                table1.buttons().container().appendTo('#accessories_wrapper .col-md-6:eq(0)');

                // update index column saat order/search
                table1.on('order.dt search.dt', function () {
                    let i = 1;
                    table1.cells(null, 0, {search: 'applied', order: 'applied'}).every(function (cell) {
                        this.data(i++);
                    });
                }).draw();
            }

            if (!$.fn.DataTable.isDataTable('#accessoriesRental')) {

                var table2 = $('#accessoriesRental').DataTable({
                    lengthChange: false,
                    buttons: [
                        {
                            extend: 'pdf',
                            exportOptions: { columns: [0,1,2,3,4] }
                        },
                        {
                            extend: 'print',
                            exportOptions: { columns: [0,1,2,3,4] }
                        }
                    ],

                    footerCallback: function (row, data, start, end, display) {
                        var api = this.api();

                        // helper parsing number
                        var intVal = function (i) {
                            return typeof i === 'string'
                                ? i.replace(/[^0-9]/g, '') * 1
                                : typeof i === 'number'
                                    ? i
                                    : 0;
                        };

                        // JUMLAHKAN QTY BERDASARKAN HASIL SEARCH
                        var totalQty = api
                            .column(4, { search: 'applied' }) // kolom Qty
                            .data()
                            .reduce(function (a, b) {
                                return a + intVal(b);
                            }, 0);

                        // Update footer kolom Qty
                        $(api.column(4).footer()).html(totalQty);
                    }
                });

                table2.buttons().container()
                    .appendTo('#accessoriesRental_wrapper .col-md-6:eq(0)');

                // Nomor urut dinamis
                table2.on('order.dt search.dt', function () {
                    let i = 1;
                    table2.cells(null, 0, { search: 'applied', order: 'applied' })
                        .every(function () {
                            this.data(i++);
                        });
                }).draw();
            }


            // 5) Init flatpickr untuk inputs yang mungkin dibuat/dinamis di runtime
            // (opsional) jika kamu menambahkan input datepicker secara dinamis, panggil initFlatpickrOn(el) setelah menambahkan.
        });
    </script>
    <script>
        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();

            let form = $(this).closest('.delete-form');

            Swal.fire({
                title: 'Delete Data?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>

@endpush
