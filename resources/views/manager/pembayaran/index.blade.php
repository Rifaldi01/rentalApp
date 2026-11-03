@extends('layouts.master')
@section('title', 'Data Cicilan')
@section('content')
    <style>
        .cicilan-card {
            border: 1px solid #e7ebf0;
            border-radius: 12px;
            box-shadow: 0 3px 14px rgba(20, 35, 60, .045);
        }

        .cicilan-heading {
            padding: 18px 20px 14px;
            border-bottom: 1px solid #edf0f4;
        }

        .cicilan-heading h4 {
            font-weight: 700;
            color: #273449;
            margin: 0;
        }

        .cicilan-heading p {
            color: #7b8794;
            margin: 4px 0 0;
            font-size: 13px;
        }

        .cicilan-filter {
            background: #fbfcfe;
            border: 1px solid #e5eaf0;
            border-radius: 9px;
            padding: 15px;
        }

        .cicilan-filter label {
            font-size: 13px;
            font-weight: 600;
            color: #465365;
            margin-bottom: 6px;
        }

        .cicilan-filter .form-control, .cicilan-filter .form-select {
            min-height: 40px;
            border-color: #dce2e9;
        }

        .cicilan-table {
            vertical-align: middle;
            font-size: 13px;
        }

        .cicilan-table thead th {
            background: #f5f7fa;
            color: #344054;
            font-weight: 700;
            white-space: nowrap;
            border-bottom: 1px solid #e3e8ef;
            padding: 12px 10px;
        }

        .cicilan-table tbody td {
            padding: 11px 10px;
            border-color: #e8ecf1;
        }

        .cicilan-invoice {
            font-weight: 700;
            color: #344054;
            white-space: nowrap;
        }

        .cicilan-customer {
            font-weight: 600;
            color: #273449;
        }

        .cicilan-subtext {
            color: #8792a2;
            font-size: 12px;
        }

        .cicilan-amount {
            font-weight: 600;
            white-space: nowrap;
        }

        .cicilan-balance-zero {
            color: #16a34a;
            font-weight: 700;
        }

        .cicilan-balance-due {
            color: #ef4444;
            font-weight: 700;
        }

        .cicilan-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-radius: 20px;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 700;
        }

        .cicilan-status.paid {
            color: #087b36;
            background: #dcfce7;
        }

        .cicilan-status.unpaid {
            color: #9a6700;
            background: #fff1c2;
        }

        @media (max-width: 767px) {
            .cicilan-heading {
                padding: 15px;
            }

            .cicilan-filter .col-md-4, .cicilan-filter .col-md-3 {
                margin-bottom: 8px;
            }
        }
    </style>
    <div class="card cicilan-card">
        <div class="cicilan-heading d-flex align-items-center">
            <div class="me-3 text-primary" style="font-size:25px"><i class="bx bx-money"></i></div>
            <div>
                <h4>Data Cicilan</h4>
                <p>Daftar transaksi piutang dan pembayaran cicilan</p>
            </div>
        </div>
        <div class="card-body p-3 p-lg-4">
            <form method="GET" action="{{ route('manager.pembayaran.index') }}" class="cicilan-filter mb-3">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-12">
                        <label for="search">Pencarian</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bx bx-search"></i></span>
                            <input type="text" id="search" name="search" class="form-control" value="{{ $search }}"
                                   placeholder="Invoice / Invoice Manual / Customer">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="year">Tahun</label>
                        <select name="year" id="year" class="form-select">
                            <option value="all" {{ $selectedYear === 'all' ? 'selected' : '' }}>Semua Tahun</option>
                            @foreach($years as $year)
                                <option
                                    value="{{ $year }}" {{ (string)$selectedYear === (string)$year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="month">Bulan</label>
                        <select name="month" id="month" class="form-select">
                            <option value="all">Semua Bulan</option>
                            @foreach([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'] as $number=>$monthName)
                                <option
                                    value="{{ $number }}" {{ (string)$selectedMonth === (string)$number ? 'selected' : '' }}>{{ $monthName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="paid" {{ $selectedStatus === 'paid' ? 'selected' : '' }}>Lunas</option>
                            <option value="unpaid" {{ $selectedStatus === 'unpaid' ? 'selected' : '' }}>Belum Lunas
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-2 d-flex gap-2">
                        <button class="btn btn-primary flex-grow-1" type="submit"><i class="bx bx-filter-alt me-1"></i>
                            Filter
                        </button>
                        <a href="{{ route('manager.pembayaran.index') }}" class="btn btn-outline-secondary"
                           title="Reset"><i class="bx bx-reset"></i></a>
                    </div>
                </div>
            </form>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div class="small text-muted">
                    Filter aktif:
                    @if($selectedYear && $selectedYear !== 'all')
                        <span class="badge bg-info text-dark">Tahun: {{ $selectedYear }}</span>
                    @endif
                    @if($selectedMonth && $selectedMonth !== 'all')
                        <span class="badge bg-info text-dark">Bulan: {{ $selectedMonth }}</span>
                    @endif
                    @if($selectedStatus)
                        <span
                            class="badge bg-info text-dark">Status: {{ $selectedStatus === 'paid' ? 'Lunas' : 'Belum Lunas' }}</span>
                    @endif
                    @if($search)
                        <span class="badge bg-info text-dark">Pencarian: {{ $search }}</span>
                    @endif
                    @if(!$selectedStatus && (!$selectedMonth || $selectedMonth === 'all') && (!$selectedYear || $selectedYear === 'all') && !$search)
                        <span class="badge bg-light text-dark border">Semua data</span>
                    @endif
                </div>
                <button type="button" class="btn btn-success btn-sm px-3" onclick="exportCicilanExcel()"><i
                        class="bx bx-spreadsheet me-1"></i> Export Excel
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered cicilan-table mb-0" id="cicilanTable">
                    <thead>
                    <tr>
                        <th class="text-center" style="width:55px">No</th>
                        <th>Tanggal</th>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th class="text-end">Total Tagihan</th>
                        <th class="text-end">Sudah Bayar</th>
                        <th class="text-end">Sisa Piutang</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width:85px">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($rentals as $key => $data)
                        @php
                            $paid = max(0, (int) $data->nominal_in + (int) ($data->debt_sum_pay_debts ?? 0));
                            $remaining = max(0, (int) $data->nominal_out);
                            $isPaid = $remaining <= 0;
                            $grandTotal = (int) ($data->total_invoice ?? ($paid + $remaining));
                        @endphp
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-nowrap">{{ $data->tgl_inv ? formatId($data->tgl_inv) : '-' }}</td>
                            <td><span class="cicilan-invoice">{{ $data->no_inv ?: '-' }}</span></td>
                            <td>
                                <div class="cicilan-customer">{{ $data->cust->name ?? 'Customer dihapus' }}</div>
                                <div class="cicilan-subtext">{{ $data->cust->phone ?? '' }}</div>
                            </td>
                            <td class="text-end cicilan-amount">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                            <td class="text-end cicilan-amount">Rp {{ number_format($paid, 0, ',', '.') }}</td>
                            <td class="text-end">
                                <span
                                    class="{{ $isPaid ? 'cicilan-balance-zero' : 'cicilan-balance-due' }}">Rp {{ number_format($remaining, 0, ',', '.') }}</span>
                            </td>
                            <td class="text-center">
                                @if($isPaid)
                                    <span class="cicilan-status paid"><i class="bx bx-check-circle"></i> Lunas</span>
                                @else
                                    <span class="cicilan-status unpaid"><i
                                            class="bx bx-time-five"></i> Belum Lunas</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('manager.pembayaran.show', $data->id) }}"
                                   class="btn btn-primary btn-sm px-3" title="Lihat detail">
                                    <i class="bx bx-show"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Data cicilan tidak ditemukan.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3">
                <small class="text-muted">Menampilkan {{ $rentals->count() }} data cicilan</small>
                <small class="text-muted">Klik ikon mata untuk melihat detail pembayaran.</small>
            </div>
        </div>
    </div>
@endsection
@push('head') @endpush
@push('js')
    <script>
        function exportCicilanExcel() {
            const tableElement = document.getElementById('cicilanTable');

            if (!tableElement) return;

            let exportTable;

            // Ambil seluruh data dari DataTables
            if ($.fn.DataTable && $.fn.DataTable.isDataTable('#cicilanTable')) {
                const dataTable = $('#cicilanTable').DataTable();

                exportTable = document.createElement('table');
                exportTable.className = tableElement.className;

                // Salin bagian header
                exportTable.appendChild(
                    tableElement.querySelector('thead').cloneNode(true)
                );

                const tbody = document.createElement('tbody');

                // Ambil seluruh baris, bukan hanya halaman aktif
                dataTable.rows({ search: 'applied', order: 'applied' })
                    .nodes()
                    .each(function (row) {
                        const clonedRow = row.cloneNode(true);

                        // Hilangkan kolom Action
                        if (clonedRow.lastElementChild) {
                            clonedRow.removeChild(clonedRow.lastElementChild);
                        }

                        tbody.appendChild(clonedRow);
                    });

                exportTable.appendChild(tbody);
            } else {
                exportTable = tableElement.cloneNode(true);

                exportTable.querySelectorAll('tr').forEach(row => {
                    if (row.lastElementChild) {
                        row.removeChild(row.lastElementChild);
                    }
                });
            }

            const html =
                '<html><head><meta charset="UTF-8"></head><body>' +
                exportTable.outerHTML +
                '</body></html>';

            const blob = new Blob(
                ['\ufeff', html],
                { type: 'application/vnd.ms-excel;charset=utf-8;' }
            );

            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');

            link.href = url;
            link.download = 'data-cicilan-{{ now()->format('Ymd') }}.xls';

            document.body.appendChild(link);
            link.click();
            link.remove();

            URL.revokeObjectURL(url);
        }
    </script>
    <script>
        $(document).ready(function () {
            if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#cicilanTable')) {
                $('#cicilanTable').DataTable({
                    pageLength: 25,
                    lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ],
                    ordering: true,
                    language: {
                        lengthMenu: 'Tampilkan _MENU_ data',
                        zeroRecords: 'Data tidak ditemukan',
                        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                        infoEmpty: 'Tidak ada data',
                        infoFiltered: '(difilter dari _MAX_ total data)',
                        search: 'Cari:',
                        paginate: {
                            first: 'Awal',
                            last: 'Akhir',
                            next: 'Berikutnya',
                            previous: 'Sebelumnya'
                        }
                    }
                });
            }
        });
    </script>
@endpush
