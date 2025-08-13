@extends('layouts.master')
@section('content')
    <div class="card table-timbang">
        <div class="card-header">
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
            <div class="mb-4 mt-4">
                <h4 class="mb-0 text-uppercase">Poin Pelanggan</h4>
            </div>
            <div class="row">
                <form id="filter" method="GET">
                    <div class="row">
                        <div class="col-3 ms-2 mt-2">
                            <label class="form-label">
                                Start Date
                            </label>
                            <input type="date" class="form-control" name="start_date" id="starDate">
                        </div>
                        <div class="col-4 mt-2">
                            <label class="form-label">
                                End Date
                            </label>
                            <input type="date" class="form-control" name="end_date" id="endDate">
                        </div>
                        <div class="col-4 mt-2">
                            <label class="form-label">
                                Pelanggan
                            </label>
                            <select name=customer"" id="customer" class="form-control">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 pt-2 float-end me-5">
                        <button type="button" id="filter-btn" class="btn btn-success btn-sm"><i class="bx bx-filter"></i> Filter</button>
                    </div>
                    <div class="col-md-1 pt-2 float-end ms-5">
                        <button type="button" id="reset-btn" class="btn btn-danger btn-sm"><i class="bx bx-x-circle"></i> Reset</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="poin" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th>Tanggal Invoice</th>
                        <th>Invoice</th>
                        <th>Pelanggan</th>
                        <th>Item</th>
                        <th>Accessories</th>
                        <th>Total Inv</th>
                    </tr>
                    </thead>
                    <tbody id="report-poin">
                    </tbody>

                    <tfoot>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
@endsection

@push('head')
    <style>
        table.dataTable {
            font-size: 13px /* Atur ukuran font */
        }
        table.dataTable td {
            padding: 3px; /* Atur padding agar lebih rapat jika diperlukan */
        }

    </style>
@endpush

@push('js')

    <script>
        $(document).ready(function () {
            var table = $('#poin').DataTable();

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
