@extends('layouts.master')

@section('title', 'Laporan Cicilan')

@section('content')
    @php
        $startDate = request('tanggal_mulai');
        $endDate = request('tanggal_akhir');

        $summaryItems = [
            [
                'label' => 'Total Uang Masuk',
                'value' => $uangmasuk ?? 0,
                'icon' => 'bx bx-wallet',
                'class' => 'primary',
            ],
            [
                'label' => 'Total Diskon',
                'value' => $diskon ?? 0,
                'icon' => 'bx bx-purchase-tag',
                'class' => 'info',
            ],
            [
                'label' => 'Total Fee',
                'value' => $totalfee ?? 0,
                'icon' => 'bx bx-receipt',
                'class' => 'warning',
            ],
            [
                'label' => 'Total Sisa Bayar',
                'value' => $sisabayar ?? 0,
                'icon' => 'bx bx-time-five',
                'class' => 'danger',
            ],
            [
                'label' => 'Total PPN',
                'value' => $totalppn ?? 0,
                'icon' => 'bx bx-calculator',
                'class' => 'secondary',
            ],
            [
                'label' => 'Total Biaya Ganti',
                'value' => $totalganti ?? 0,
                'icon' => 'bx bx-revision',
                'class' => 'secondary',
            ],
            [
                'label' => 'Total Bersih',
                'value' => $totalbersih ?? 0,
                'icon' => 'bx bx-trending-up',
                'class' => 'success',
            ],
        ];
    @endphp

    <style>
        .cicilan-report {
            --cr-primary: #4b266a;
            --cr-primary-soft: rgba(75, 38, 106, .08);
            --cr-border: #e8edf3;
            --cr-muted: #778398;
            color: #263247;
        }

        .cicilan-report .report-heading {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .cicilan-report .report-heading-icon {
            width: 48px;
            height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            color: var(--cr-primary);
            background: var(--cr-primary-soft);
            font-size: 25px;
            flex: 0 0 auto;
        }

        .cicilan-report .report-subtitle {
            color: var(--cr-muted);
            font-size: 13px;
        }

        .cicilan-report .filter-card,
        .cicilan-report .summary-card,
        .cicilan-report .table-card {
            border: 1px solid var(--cr-border);
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(30, 41, 59, .045);
        }

        .cicilan-report .filter-card .card-body {
            padding: 20px;
        }

        .cicilan-report .form-label {
            font-size: 12px;
            font-weight: 700;
            color: #566176;
            margin-bottom: 7px;
        }

        .cicilan-report .form-control {
            min-height: 42px;
            border-color: #dfe5ed;
            border-radius: 9px;
            font-size: 13px;
        }

        .cicilan-report .form-control:focus {
            border-color: var(--cr-primary);
            box-shadow: 0 0 0 .2rem rgba(75, 38, 106, .10);
        }

        .cicilan-report .btn-filter {
            min-height: 42px;
            border-radius: 9px;
            background: var(--cr-primary);
            border-color: var(--cr-primary);
            font-weight: 600;
            padding: 8px 18px;
        }

        .cicilan-report .btn-filter:hover {
            background: #3d1e57;
            border-color: #3d1e57;
        }

        .cicilan-report .btn-reset {
            min-height: 42px;
            border-radius: 9px;
            font-weight: 600;
        }

        .cicilan-report .summary-card {
            height: 100%;
            overflow: hidden;
        }

        .cicilan-report .summary-card .card-body {
            padding: 16px;
        }

        .cicilan-report .summary-icon {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            font-size: 20px;
            flex: 0 0 auto;
        }

        .cicilan-report .summary-label {
            color: var(--cr-muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .35px;
        }

        .cicilan-report .summary-value {
            font-size: 16px;
            font-weight: 700;
            line-height: 1.35;
            color: #263247;
            overflow-wrap: anywhere;
        }

        .cicilan-report .summary-primary { color: #4b266a; background: rgba(75, 38, 106, .10); }
        .cicilan-report .summary-info { color: #087e8b; background: rgba(8, 126, 139, .10); }
        .cicilan-report .summary-warning { color: #a66a00; background: rgba(245, 158, 11, .14); }
        .cicilan-report .summary-danger { color: #c0394b; background: rgba(220, 53, 69, .10); }
        .cicilan-report .summary-secondary { color: #64748b; background: rgba(100, 116, 139, .12); }
        .cicilan-report .summary-success { color: #16804a; background: rgba(22, 128, 74, .10); }

        .cicilan-report .table-card .card-header {
            background: #fff;
            border-bottom: 1px solid var(--cr-border);
            padding: 18px 20px;
            border-radius: 14px 14px 0 0;
        }

        .cicilan-report .table-card .card-body {
            padding: 18px;
        }

        .cicilan-report .table-responsive {
            border: 1px solid var(--cr-border);
            border-radius: 10px;
        }

        .cicilan-report #table-report-cicilan {
            min-width: 1850px;
            margin-bottom: 0 !important;
            font-size: 12px;
            vertical-align: middle;
        }

        .cicilan-report #table-report-cicilan thead th {
            background: #f5f6fa;
            color: #475166;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .25px;
            white-space: nowrap;
            padding: 12px 10px;
            border-bottom: 1px solid #e3e8ef;
            vertical-align: middle;
        }

        .cicilan-report #table-report-cicilan tbody td {
            padding: 10px;
            border-color: #edf0f4;
            color: #465166;
        }

        .cicilan-report #table-report-cicilan tbody tr:hover {
            background: #faf9fc;
        }

        .cicilan-report .invoice-number {
            color: var(--cr-primary);
            font-weight: 700;
            white-space: nowrap;
        }

        .cicilan-report .customer-name {
            min-width: 140px;
            font-weight: 600;
            color: #263247;
        }

        .cicilan-report .item-list {
            min-width: 160px;
            margin: 0;
            padding-left: 17px;
        }

        .cicilan-report .item-list li {
            margin-bottom: 3px;
        }

        .cicilan-report .serial-list {
            min-width: 110px;
            margin: 0;
            padding-left: 17px;
        }

        .cicilan-report .money-cell {
            white-space: nowrap;
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        .cicilan-report .status-badge {
            display: inline-block;
            min-width: 72px;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .25px;
        }

        .cicilan-report .status-rent { color: #16804a; background: #e8f6ee; }
        .cicilan-report .status-finished { color: #64748b; background: #eef1f5; }
        .cicilan-report .status-problem { color: #c0394b; background: #fdecef; }
        .cicilan-report .status-unknown { color: #8a6500; background: #fff6dc; }

        .cicilan-report #table-report-cicilan tfoot th,
        .cicilan-report #table-report-cicilan tfoot td {
            background: #f8f9fc;
            padding: 10px;
            border-top: 1px solid #e3e8ef;
            font-size: 12px;
        }

        .cicilan-report #table-report-cicilan tfoot .summary-footer-label {
            text-align: right;
            font-weight: 700;
            color: #475166;
        }

        .cicilan-report #table-report-cicilan tfoot .summary-footer-value {
            text-align: right;
            white-space: nowrap;
            font-weight: 700;
            color: #263247;
        }

        .cicilan-report .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #dfe5ed;
            border-radius: 8px;
            padding: 6px 10px;
            margin-left: 7px;
        }

        .cicilan-report .dataTables_wrapper .dataTables_length select {
            border: 1px solid #dfe5ed;
            border-radius: 8px;
            padding: 5px 24px 5px 8px;
        }

        .cicilan-report .dt-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 12px;
        }

        .cicilan-report .dt-buttons .btn {
            border-radius: 8px !important;
            font-size: 12px;
            font-weight: 600;
        }

        .cicilan-report .empty-state {
            padding: 35px 15px !important;
            text-align: center;
            color: var(--cr-muted) !important;
        }

        @media (max-width: 767.98px) {
            .cicilan-report .report-heading {
                align-items: flex-start;
            }

            .cicilan-report .filter-card .card-body,
            .cicilan-report .table-card .card-body {
                padding: 14px;
            }

            .cicilan-report .summary-value {
                font-size: 14px;
            }
        }
    </style>

    <div class="cicilan-report">
        {{-- Header halaman --}}
        <div class="card table-card mb-4">
            <div class="card-body p-3 p-md-4">
                <div class="report-heading">
                    <div class="report-heading-icon">
                        <i class="bx bx-spreadsheet"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold">Laporan Cicilan</h4>
                        <div class="report-subtitle">
                            Ringkasan pembayaran, informasi invoice, dan status transaksi rental.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notifikasi validasi --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <div class="d-flex align-items-start">
                    <i class="bx bx-error-circle fs-4 me-2"></i>
                    <div>
                        <strong>Filter belum dapat diproses.</strong>
                        <ul class="mb-0 mt-1 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        {{-- Filter periode --}}
        <div class="card filter-card mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                    <div>
                        <h6 class="fw-bold mb-1">
                            <i class="bx bx-filter-alt me-1"></i> Filter Periode Laporan
                        </h6>
                        <small class="text-muted">Pilih tanggal mulai dan tanggal akhir untuk melihat data cicilan.</small>
                    </div>
                    @if ($startDate || $endDate)
                        <span class="badge bg-light text-dark border px-3 py-2">
                            <i class="bx bx-calendar me-1"></i>
                            {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : '-' }}
                            –
                            {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : '-' }}
                        </span>
                    @endif
                </div>

                <form action="{{ route('admin.report.filtercicilan') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-4">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input
                                type="date"
                                id="tanggal_mulai"
                                class="form-control"
                                name="tanggal_mulai"
                                value="{{ $startDate }}"
                                required
                            >
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                            <input
                                type="date"
                                id="tanggal_akhir"
                                class="form-control"
                                name="tanggal_akhir"
                                value="{{ $endDate }}"
                                min="{{ $startDate }}"
                                required
                            >
                        </div>

                        <div class="col-12 col-md-4 d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary btn-filter">
                                <i class="bx bx-search me-1"></i> Terapkan Filter
                            </button>
                            <a href="{{ route('admin.report.filtercicilan') }}" class="btn btn-outline-secondary btn-reset">
                                <i class="bx bx-refresh me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Ringkasan keuangan --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div>
                <h6 class="fw-bold mb-1">Ringkasan Keuangan</h6>
                <small class="text-muted">Akumulasi nilai berdasarkan periode laporan yang dipilih.</small>
            </div>
        </div>

        <div class="row g-3 mb-4">
            @foreach ($summaryItems as $summary)
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card summary-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="summary-icon summary-{{ $summary['class'] }}">
                                    <i class="{{ $summary['icon'] }}"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="summary-label">{{ $summary['label'] }}</div>
                                    <div class="summary-value">{{ formatRupiah($summary['value']) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Detail transaksi --}}
        <div class="card table-card">
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h6 class="fw-bold mb-1">Detail Transaksi Cicilan</h6>
                        <small class="text-muted">
                            Data pembayaran per cicilan beserta rincian rental dan informasi penerima.
                        </small>
                    </div>
                    <span class="badge bg-light text-dark border px-3 py-2">
                        {{ isset($cicilan) ? $cicilan->count() : 0 }} data pembayaran
                    </span>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-report-cicilan" class="table table-hover table-bordered align-middle w-100">
                        <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Tanggal Invoice</th>
                            <th>Nomor Invoice</th>
                            <th>Tanggal Bayar</th>
                            <th>Pelanggan</th>
                            <th>Item</th>
                            <th>Nomor Seri</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Total Invoice</th>
                            <th>PPN</th>
                            <th>Fee</th>
                            <th>Diskon</th>
                            <th>Biaya Ganti</th>
                            <th>Total Tagihan</th>
                            <th>Uang Masuk</th>
                            <th>Sisa Bayar</th>
                            <th>Bank / Keterangan</th>
                            <th>Penerima</th>
                            <th class="text-center">Tanggal Bayar</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse ($cicilan as $key => $datas)
                            @php
                                $rental = $datas->rental;
                                $itemIds = $rental ? json_decode($rental->item_id, true) : [];
                                $itemIds = is_array($itemIds) ? $itemIds : [];

                                $itemNames = [];
                                $itemSerials = [];

                                foreach ($itemIds as $itemId) {
                                    $itemData = \App\Models\Item::find($itemId);

                                    if ($itemData) {
                                        $itemNames[] = $itemData->name;
                                        $itemSerials[] = $itemData->no_seri;
                                    } else {
                                        $itemNames[] = 'Item tidak ditemukan';
                                        $itemSerials[] = '-';
                                    }
                                }

                                $status = $rental->status ?? null;
                            @endphp

                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>

                                <td class="text-nowrap">
                                    {{ $rental && $rental->tgl_inv ? formatId($rental->tgl_inv) : '-' }}
                                </td>

                                <td>
                                    <span class="invoice-number">{{ $rental->no_inv ?? '-' }}</span>
                                </td>

                                <td class="text-nowrap">
                                    {{ $datas->date_pay ? formatId($datas->date_pay) : '-' }}
                                </td>

                                <td>
                                    <div class="customer-name">
                                        {{ $rental->cust->name ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    @if (count($itemNames))
                                        <ul class="item-list">
                                            @foreach ($itemNames as $itemName)
                                                <li>{{ $itemName }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if (count($itemSerials))
                                        <ul class="serial-list">
                                            @foreach ($itemSerials as $serial)
                                                <li>{{ $serial ?: '-' }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="text-nowrap">
                                    {{ $rental && $rental->date_start ? formatId($rental->date_start) : '-' }}
                                </td>

                                <td class="text-nowrap">
                                    {{ $rental && $rental->date_end ? formatId($rental->date_end) : '-' }}
                                </td>

                                <td class="money-cell">
                                    {{ formatRupiah($rental->total_invoice ?? 0) }}
                                </td>

                                <td class="money-cell">
                                    {{ formatRupiah($rental->ppn ?? 0) }}
                                </td>

                                <td class="money-cell">
                                    {{ formatRupiah($rental->fee ?? 0) }}
                                </td>

                                <td class="money-cell">
                                    {{ formatRupiah($rental->diskon ?? 0) }}
                                </td>

                                <td class="money-cell">
                                    {{ formatRupiah($rental->biaya_ganti ?? 0) }}
                                </td>

                                <td class="money-cell fw-semibold">
                                    {{ formatRupiah($total[$datas->id] ?? 0) }}
                                </td>

                                <td class="money-cell fw-semibold text-success">
                                    {{ formatRupiah($datas->pay_debts ?? 0) }}
                                </td>

                                <td class="money-cell">
                                    @if ($rental && $rental->nominal_out !== null)
                                        {{ formatRupiah($rental->nominal_out) }}
                                    @else
                                        {{ formatRupiah($sisa[$datas->id] ?? 0) }}
                                    @endif
                                </td>

                                <td>
                                    @if ($datas->bank_id)
                                        {{ $datas->bank->name ?? '-' }}
                                    @else
                                        {{ $datas->description ?: '-' }}
                                    @endif
                                </td>

                                <td>{{ $datas->penerima ?: '-' }}</td>

                                <td class="text-center">
                                    {{ formatId($datas->date_pay ?: '-' )}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="20" class="empty-state">
                                    <i class="bx bx-receipt fs-2 d-block mb-2"></i>
                                    <strong>Belum ada data pembayaran</strong>
                                    <div class="small mt-1">Silakan pilih periode tanggal lain atau periksa kembali filter laporan.</div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>

                        <tfoot>
                        @php
                            $footerSummaries = [
                                'Total Uang Masuk' => $uangmasuk ?? 0,
                                'Total Diskon' => $diskon ?? 0,
                                'Total Fee' => $totalfee ?? 0,
                                'Total Sisa Bayar' => $sisabayar ?? 0,
                                'Total PPN' => $totalppn ?? 0,
                                'Total Biaya Ganti' => $totalganti ?? 0,
                                'Total Bersih' => $totalbersih ?? 0,
                            ];
                        @endphp

                        @foreach ($footerSummaries as $label => $value)
                            <tr>
                                <th colspan="18" class="summary-footer-label">{{ $label }}</th>
                                <th colspan="2" class="summary-footer-value">{{ formatRupiah($value) }}</th>
                            </tr>
                        @endforeach
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                    <small class="text-muted">
                        <i class="bx bx-info-circle me-1"></i>
                        Gunakan kolom pencarian untuk menemukan invoice, pelanggan, bank, atau informasi lainnya.
                    </small>
                    <small class="text-muted">Nilai dalam Rupiah (IDR).</small>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {

            const tableSelector = '#table-report-cicilan';

            if (!$.fn.DataTable || !$(tableSelector).length) {
                return;
            }

            if ($.fn.DataTable.isDataTable(tableSelector)) {
                $(tableSelector).DataTable().destroy();
            }

            // =========================================================
            // HELPER: BERSIHKAN HTML
            // =========================================================

            function getPlainText(data) {
                return $('<div>')
                    .html(data)
                    .text()
                    .trim()
                    .replace(/\s+/g, ' ');
            }

            // =========================================================
            // HELPER: FORMAT ITEM DAN NOMOR SERI
            // =========================================================

            function formatListCell(data) {

                const $content = $('<div>').html(data);
                const listItems = $content.find('li');

                if (listItems.length > 0) {

                    const items = [];

                    listItems.each(function () {

                        const value = $(this)
                            .text()
                            .replace(/\s+/g, ' ')
                            .trim();

                        if (value) {
                            items.push('• ' + value);
                        }
                    });

                    return items.join('\n');
                }

                $content.find('br').replaceWith('\n');

                return $content.text()
                    .split('\n')
                    .map(function (item) {
                        return item.trim();
                    })
                    .filter(Boolean)
                    .map(function (item) {
                        return '• ' + item;
                    })
                    .join('\n');
            }

            // =========================================================
            // HELPER: KONVERSI RUPIAH KE ANGKA ASLI
            // =========================================================

            function parseRupiah(data) {

                let text = $('<div>').html(data).text().trim();

                if (!text || text === '-') {
                    return 0;
                }

                /*
                 * Contoh:
                 * Rp 600.000   => 600000
                 * Rp 1.500.000 => 1500000
                 * 600.000      => 600000
                 * 750.000      => 750000
                 *
                 * Jangan menggunakan parseFloat('600.000')
                 * karena hasilnya adalah 600.
                 */

                text = text
                    .replace(/Rp/gi, '')
                    .replace(/\s/g, '')
                    .replace(/[^\d-]/g, '');

                if (!text || text === '-') {
                    return 0;
                }

                const number = Number(text);

                return Number.isFinite(number) ? number : 0;
            }

            // =========================================================
            // HELPER: FORMAT EXCEL
            // =========================================================

            function formatExcelCell(data, row, column, node) {

                // Item dan Nomor Seri
                if (column === 5 || column === 6) {
                    return formatListCell(data);
                }

                /*
                 * Kolom nominal:
                 * 9  = Total Invoice
                 * 10 = PPN
                 * 11 = Fee
                 * 12 = Diskon
                 * 13 = Biaya Ganti
                 * 14 = Total Tagihan
                 * 15 = Uang Masuk
                 * 16 = Sisa Bayar
                 */

                if (column >= 9 && column <= 16) {
                    return parseRupiah(data);
                }

                return getPlainText(data);
            }

            // =========================================================
            // HELPER: FORMAT PDF DAN PRINT
            // =========================================================

            function formatDocumentCell(data, row, column, node) {

                if (column === 5 || column === 6) {
                    return formatListCell(data);
                }

                return getPlainText(data);
            }

            // =========================================================
            // DATATABLE
            // =========================================================

            const table = $(tableSelector).DataTable({

                pageLength: 25,

                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'Semua']
                ],

                lengthChange: true,
                ordering: true,
                searching: true,
                processing: false,
                autoWidth: false,
                scrollX: true,

                order: [[1, 'desc']],

                columnDefs: [
                    {
                        targets: 0,
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        targets: [9, 10, 11, 12, 13, 14, 15, 16],
                        className: 'text-end'
                    },
                    {
                        targets: 19,
                        orderable: false,
                        searchable: true,
                        className: 'text-center'
                    }
                ],

                language: {
                    search: 'Cari data:',
                    searchPlaceholder: 'Invoice, pelanggan, bank...',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                    infoEmpty: 'Tidak ada data untuk ditampilkan',
                    infoFiltered: '(difilter dari _MAX_ total data)',
                    zeroRecords: 'Data yang dicari tidak ditemukan',
                    emptyTable: 'Belum ada data pembayaran',

                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Berikutnya',
                        previous: 'Sebelumnya'
                    }
                },

                dom:
                    "<'row align-items-center mb-2'<'col-md-6'B><'col-md-6'f>>" +
                    "<'row'<'col-12'tr>>" +
                    "<'row align-items-center mt-3'<'col-md-5'i><'col-md-7'p>>",

                buttons: [

                    // =================================================
                    // EXPORT EXCEL
                    // =================================================

                    {
                        extend: 'excelHtml5',

                        text: '<i class="bx bx-file me-1"></i> Excel',

                        className: 'btn btn-outline-success',

                        title: 'Laporan Cicilan',

                        filename: function () {

                            const now = new Date();
                            const date = now.toISOString().slice(0, 10);

                            return 'Laporan_Cicilan_' + date;
                        },

                        exportOptions: {
                            columns: ':visible',
                            footer: false,

                            format: {
                                header: function (data) {
                                    return $('<div>').html(data).text().trim();
                                },

                                body: function (data, row, column, node) {

                                    // Nomor urut otomatis ketika export Excel.
                                    if (column === 0) {
                                        return row + 1;
                                    }

                                    // Kolom lainnya tetap menggunakan formatter sebelumnya.
                                    return formatExcelCell(data, row, column, node);
                                }
                            }
                        },

                        footer: false,

                        customize: function (xlsx) {

                            const sheet = xlsx.xl.worksheets['sheet1.xml'];
                            const styles = xlsx.xl['styles.xml'];

                            const namespace =
                                'http://schemas.openxmlformats.org/spreadsheetml/2006/main';

                            const sheetData =
                                sheet.getElementsByTagName('sheetData')[0];

                            const cellXfs =
                                styles.getElementsByTagName('cellXfs')[0];

                            // =============================================
                            // 1. STYLE WRAP TEXT
                            // =============================================

                            const wrapStyleIndex =
                                cellXfs.getElementsByTagName('xf').length;

                            const wrapStyle = document.createElementNS(
                                namespace,
                                'xf'
                            );

                            wrapStyle.setAttribute('numFmtId', '0');
                            wrapStyle.setAttribute('fontId', '0');
                            wrapStyle.setAttribute('fillId', '0');
                            wrapStyle.setAttribute('borderId', '0');
                            wrapStyle.setAttribute('xfId', '0');
                            wrapStyle.setAttribute('applyAlignment', '1');

                            const wrapAlignment = document.createElementNS(
                                namespace,
                                'alignment'
                            );

                            wrapAlignment.setAttribute('vertical', 'top');
                            wrapAlignment.setAttribute('horizontal', 'left');
                            wrapAlignment.setAttribute('wrapText', '1');

                            wrapStyle.appendChild(wrapAlignment);
                            cellXfs.appendChild(wrapStyle);

                            // =============================================
                            // 2. STYLE ANGKA RUPIAH
                            // =============================================

                            const numberStyleIndex =
                                cellXfs.getElementsByTagName('xf').length;

                            const numberStyle = document.createElementNS(
                                namespace,
                                'xf'
                            );

                            /*
                             * numFmtId 3 = #,##0
                             * Nilai tetap angka asli, bukan teks.
                             */

                            numberStyle.setAttribute('numFmtId', '3');
                            numberStyle.setAttribute('fontId', '0');
                            numberStyle.setAttribute('fillId', '0');
                            numberStyle.setAttribute('borderId', '0');
                            numberStyle.setAttribute('xfId', '0');
                            numberStyle.setAttribute('applyNumberFormat', '1');
                            numberStyle.setAttribute('applyAlignment', '1');

                            const numberAlignment = document.createElementNS(
                                namespace,
                                'alignment'
                            );

                            numberAlignment.setAttribute('horizontal', 'right');
                            numberAlignment.setAttribute('vertical', 'center');

                            numberStyle.appendChild(numberAlignment);
                            cellXfs.appendChild(numberStyle);

                            // Perbarui jumlah style
                            cellXfs.setAttribute(
                                'count',
                                cellXfs.getElementsByTagName('xf').length
                            );

                            // =============================================
                            // 3. TERAPKAN STYLE KE KOLOM EXCEL
                            // =============================================

                            const cells = sheet.getElementsByTagName('c');

                            for (let i = 0; i < cells.length; i++) {

                                const cell = cells[i];
                                const reference = cell.getAttribute('r');

                                if (!reference) {
                                    continue;
                                }

                                // Item dan Nomor Seri: F dan G
                                if (/^[FG]\d+$/.test(reference)) {
                                    cell.setAttribute('s', wrapStyleIndex);
                                }

                                // Nominal Rupiah: J sampai Q
                                if (/^[JKLMNOPQ]\d+$/.test(reference)) {

                                    const valueElement =
                                        cell.getElementsByTagName('v')[0];

                                    if (valueElement) {
                                        cell.setAttribute('s', numberStyleIndex);
                                    }
                                }
                            }

                            // =============================================
                            // 4. ATUR LEBAR KOLOM
                            // =============================================

                            const columnWidths = [
                                7, 15, 22, 15, 24,
                                35, 25, 15, 15, 19,
                                14, 14, 14, 17, 19,
                                17, 17, 23, 20, 17
                            ];

                            let cols = sheet.getElementsByTagName('cols')[0];

                            if (!cols) {

                                cols = document.createElementNS(
                                    namespace,
                                    'cols'
                                );

                                const sheetFormatPr =
                                    sheet.getElementsByTagName('sheetFormatPr')[0];

                                if (sheetFormatPr) {
                                    sheet.insertBefore(cols, sheetFormatPr.nextSibling);
                                } else {
                                    sheet.insertBefore(
                                        cols,
                                        sheetData
                                    );
                                }
                            }

                            while (cols.firstChild) {
                                cols.removeChild(cols.firstChild);
                            }

                            columnWidths.forEach(function (width, index) {

                                const col = document.createElementNS(
                                    namespace,
                                    'col'
                                );

                                col.setAttribute('min', index + 1);
                                col.setAttribute('max', index + 1);
                                col.setAttribute('width', width);
                                col.setAttribute('customWidth', '1');

                                cols.appendChild(col);
                            });

                            // =============================================
                            // 5. ATUR TINGGI BARIS
                            // =============================================

                            const existingRows =
                                sheetData.getElementsByTagName('row');

                            for (let i = 0; i < existingRows.length; i++) {

                                const row = existingRows[i];

                                row.setAttribute(
                                    'ht',
                                    i === 0 ? '30' : '24'
                                );

                                row.setAttribute('customHeight', '1');
                            }

                            // =============================================
                            // 6. TAMBAHKAN RINGKASAN
                            // =============================================

                            let lastRow = 0;

                            for (let i = 0; i < existingRows.length; i++) {

                                const rowNumber = parseInt(
                                    existingRows[i].getAttribute('r'),
                                    10
                                );

                                if (rowNumber > lastRow) {
                                    lastRow = rowNumber;
                                }
                            }

                            const summary = [
                                {
                                    label: 'TOTAL UANG MASUK',
                                    value: Number(@json((float) ($uangmasuk ?? 0)))
                                },
                                {
                                    label: 'TOTAL DISKON',
                                    value: Number(@json((float) ($diskon ?? 0)))
                                },
                                {
                                    label: 'TOTAL FEE',
                                    value: Number(@json((float) ($totalfee ?? 0)))
                                },
                                {
                                    label: 'TOTAL SISA BAYAR',
                                    value: Number(@json((float) ($sisabayar ?? 0)))
                                },
                                {
                                    label: 'TOTAL PPN',
                                    value: Number(@json((float) ($totalppn ?? 0)))
                                },
                                {
                                    label: 'TOTAL BIAYA GANTI',
                                    value: Number(@json((float) ($totalganti ?? 0)))
                                },
                                {
                                    label: 'TOTAL BERSIH',
                                    value: Number(@json((float) ($totalbersih ?? 0)))
                                }
                            ];

                            function createCell(reference, value, isText) {

                                const cell = document.createElementNS(
                                    namespace,
                                    'c'
                                );

                                cell.setAttribute('r', reference);

                                if (isText) {

                                    cell.setAttribute('t', 'inlineStr');

                                    const inlineString = document.createElementNS(
                                        namespace,
                                        'is'
                                    );

                                    const text = document.createElementNS(
                                        namespace,
                                        't'
                                    );

                                    text.textContent = value;

                                    inlineString.appendChild(text);
                                    cell.appendChild(inlineString);

                                } else {

                                    cell.setAttribute('t', 'n');
                                    cell.setAttribute('s', numberStyleIndex);

                                    const number = document.createElementNS(
                                        namespace,
                                        'v'
                                    );

                                    number.textContent =
                                        Number.isFinite(value) ? value : 0;

                                    cell.appendChild(number);
                                }

                                return cell;
                            }

                            // Baris kosong
                            lastRow++;

                            const emptyRow = document.createElementNS(
                                namespace,
                                'row'
                            );

                            emptyRow.setAttribute('r', lastRow);
                            sheetData.appendChild(emptyRow);

                            // Judul ringkasan
                            lastRow++;

                            const titleRow = document.createElementNS(
                                namespace,
                                'row'
                            );

                            titleRow.setAttribute('r', lastRow);

                            titleRow.appendChild(
                                createCell(
                                    'C' + lastRow,
                                    'RINGKASAN LAPORAN CICILAN',
                                    true
                                )
                            );

                            sheetData.appendChild(titleRow);

                            // Header ringkasan
                            lastRow++;

                            const headerRow = document.createElementNS(
                                namespace,
                                'row'
                            );

                            headerRow.setAttribute('r', lastRow);

                            headerRow.appendChild(
                                createCell('C' + lastRow, 'KETERANGAN', true)
                            );

                            headerRow.appendChild(
                                createCell('D '+ lastRow, 'NILAI (RP)', true)
                            );

                            sheetData.appendChild(headerRow);

                            // Isi ringkasan
                            summary.forEach(function (item) {

                                lastRow++;

                                const row = document.createElementNS(
                                    namespace,
                                    'row'
                                );

                                row.setAttribute('r', lastRow);

                                row.appendChild(
                                    createCell(
                                        'C' + lastRow,
                                        item.label,
                                        true
                                    )
                                );

                                row.appendChild(
                                    createCell(
                                        'D' + lastRow,
                                        item.value,
                                        false
                                    )
                                );

                                sheetData.appendChild(row);
                            });

                            // =============================================
                            // 7. PERBARUI DIMENSI WORKSHEET
                            // =============================================

                            const dimension =
                                sheet.getElementsByTagName('dimension')[0];

                            if (dimension) {

                                dimension.setAttribute(
                                    'ref',
                                    'A1:T' + lastRow
                                );
                            }
                        }
                    },

                    // =================================================
                    // EXPORT PDF
                    // =================================================

                    // =================================================
// EXPORT PDF
// =================================================

                    {
                        extend: 'pdfHtml5',

                        text: '<i class="bx bxs-file-pdf me-1"></i> PDF',

                        className: 'btn btn-outline-danger',

                        title: 'Laporan Cicilan',

                        filename: 'Laporan_Cicilan',

                        orientation: 'landscape',
                        pageSize: 'A3',

                        exportOptions: {
                            columns: ':visible',
                            footer: false,

                            modifier: {
                                search: 'applied',
                                order: 'applied'
                            },

                            format: {
                                header: function (data) {
                                    return getPlainText(data);
                                },

                                body: function (data, row, column, node) {
                                    return formatDocumentCell(data, row, column, node);
                                }
                            }
                        },

                        footer: false,

                        customize: function (doc) {

                            // =====================================================
                            // 1. PENGATURAN HALAMAN
                            // =====================================================

                            doc.pageSize = 'A3';
                            doc.pageOrientation = 'landscape';

                            doc.pageMargins = [12, 28, 12, 28];

                            doc.defaultStyle = {
                                font: 'Roboto',
                                fontSize: 5.5,
                                color: '#263247',
                                lineHeight: 1.1
                            };

                            // =====================================================
                            // 2. JUDUL DOKUMEN
                            // =====================================================

                            doc.styles.title = {
                                fontSize: 13,
                                bold: true,
                                color: '#263247',
                                alignment: 'center',
                                margin: [0, 0, 0, 12]
                            };

                            // =====================================================
                            // 3. HEADER TABEL
                            // =====================================================

                            doc.styles.tableHeader = {
                                bold: true,
                                fontSize: 5.5,
                                color: '#263247',
                                fillColor: '#E9EDF5',
                                alignment: 'center',
                                margin: [2, 4, 2, 4]
                            };

                            // =====================================================
                            // 4. CARI TABEL UTAMA
                            // =====================================================

                            const tableNode = doc.content.find(function (item) {
                                return item.table !== undefined;
                            });

                            if (!tableNode) {
                                return;
                            }

                            // =====================================================
                            // 5. ATUR LEBAR 20 KOLOM
                            // =====================================================

                            /*
                             * Total lebar kolom = 1080 pt.
                             *
                             * Area halaman A3 landscape:
                             * sekitar 1190 pt.
                             *
                             * Setelah margin kiri dan kanan:
                             * sekitar 1166 pt.
                             *
                             * Jadi seluruh kolom dapat masuk ke halaman.
                             */

                            tableNode.table.widths = [
                                22, // 0  No
                                52, // 1  Tanggal Invoice
                                70, // 2  Nomor Invoice
                                52, // 3  Tanggal Bayar
                                68, // 4  Pelanggan
                                90, // 5  Item
                                82, // 6  Nomor Seri
                                49, // 7  Tanggal Mulai
                                49, // 8  Tanggal Selesai
                                54, // 9  Total Invoice
                                32, // 10 PPN
                                35, // 11 Fee
                                38, // 12 Diskon
                                42, // 13 Biaya Ganti
                                54, // 14 Total Tagihan
                                50, // 15 Uang Masuk
                                50, // 16 Sisa Bayar
                                78, // 17 Bank / Keterangan
                                58, // 18 Penerima
                                55  // 19 Tanggal Pembayaran
                            ];

                            // Ulangi header di setiap halaman.
                            tableNode.table.headerRows = 1;

                            // =====================================================
                            // 6. NOMOR URUT PDF
                            // =====================================================

                            /*
                             * Penomoran dilakukan setelah DataTables
                             * membentuk data export.
                             *
                             * Dengan demikian nomor selalu 1, 2, 3, ...
                             * meskipun data menggunakan filter atau sorting.
                             */

                            tableNode.table.body.forEach(function (row, index) {

                                if (index === 0) {
                                    return;
                                }

                                if (row[0] && typeof row[0] === 'object') {
                                    row[0].text = String(index);
                                } else {
                                    row[0] = String(index);
                                }
                            });

                            // =====================================================
                            // 7. FORMAT SEL TABEL
                            // =====================================================

                            tableNode.table.body.forEach(function (row, rowIndex) {

                                row.forEach(function (cell, columnIndex) {

                                    const isHeader = rowIndex === 0;

                                    // Jika cell masih berupa string/angka.
                                    if (typeof cell !== 'object' || cell === null) {

                                        cell = {
                                            text: String(cell ?? '')
                                        };

                                        row[columnIndex] = cell;
                                    }

                                    cell.fontSize = isHeader ? 5.5 : 5.2;

                                    cell.margin = isHeader
                                        ? [2, 4, 2, 4]
                                        : [2, 3, 2, 3];

                                    cell.valign = 'top';

                                    // Header selalu rata tengah.
                                    if (isHeader) {
                                        cell.alignment = 'center';
                                    }

                                    // Kolom nomor.
                                    else if (columnIndex === 0) {
                                        cell.alignment = 'center';
                                    }

                                    // Kolom nominal Rupiah.
                                    else if (columnIndex >= 9 && columnIndex <= 16) {
                                        cell.alignment = 'right';
                                    }

                                    // Kolom tanggal.
                                    else if ([1, 3, 7, 8, 19].includes(columnIndex)) {
                                        cell.alignment = 'center';
                                    }

                                    // Kolom lainnya.
                                    else {
                                        cell.alignment = 'left';
                                    }
                                });
                            });

                            // =====================================================
                            // 8. LAYOUT TABEL
                            // =====================================================

                            tableNode.layout = {

                                hLineWidth: function (i, node) {
                                    return 0.4;
                                },

                                vLineWidth: function (i, node) {
                                    return 0.3;
                                },

                                hLineColor: function (i, node) {
                                    return '#D5DCE5';
                                },

                                vLineColor: function (i, node) {
                                    return '#D5DCE5';
                                },

                                paddingLeft: function (i) {
                                    return 2;
                                },

                                paddingRight: function (i) {
                                    return 2;
                                },

                                paddingTop: function (i) {
                                    return 3;
                                },

                                paddingBottom: function (i) {
                                    return 3;
                                }
                            };

                            // =====================================================
                            // 9. FORMAT RUPIAH
                            // =====================================================

                            function formatRupiahPDF(value) {

                                return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
                            }

                            // =====================================================
                            // 10. DATA RINGKASAN
                            // =====================================================

                            const summaryData = [
                                [
                                    'Total Uang Masuk',
                                    formatRupiahPDF(@json((float) ($uangmasuk ?? 0)))
                                ],
                                [
                                    'Total Diskon',
                                    formatRupiahPDF(@json((float) ($diskon ?? 0)))
                                ],
                                [
                                    'Total Fee',
                                    formatRupiahPDF(@json((float) ($totalfee ?? 0)))
                                ],
                                [
                                    'Total Sisa Bayar',
                                    formatRupiahPDF(@json((float) ($sisabayar ?? 0)))
                                ],
                                [
                                    'Total PPN',
                                    formatRupiahPDF(@json((float) ($totalppn ?? 0)))
                                ],
                                [
                                    'Total Biaya Ganti',
                                    formatRupiahPDF(@json((float) ($totalganti ?? 0)))
                                ],
                                [
                                    'Total Bersih',
                                    formatRupiahPDF(@json((float) ($totalbersih ?? 0)))
                                ]
                            ];

                            // =====================================================
                            // 11. JUDUL RINGKASAN
                            // =====================================================

                            doc.content.push({

                                text: 'RINGKASAN TOTAL',

                                fontSize: 9,

                                bold: true,

                                color: '#263247',

                                margin: [0, 15, 0, 7],

                                alignment: 'left'
                            });

                            // =====================================================
                            // 12. TABEL RINGKASAN
                            // =====================================================

                            doc.content.push({

                                table: {

                                    widths: ['*', 150],

                                    body: [

                                        [
                                            {
                                                text: 'Keterangan',
                                                bold: true,
                                                fillColor: '#E9EDF5'
                                            },
                                            {
                                                text: 'Nilai',
                                                bold: true,
                                                alignment: 'right',
                                                fillColor: '#E9EDF5'
                                            }
                                        ],

                                        ...summaryData.map(function (item, index) {

                                            const isLast = index === summaryData.length - 1;

                                            return [

                                                {
                                                    text: item[0],
                                                    bold: isLast,
                                                    fillColor: isLast ? '#F1EDF5' : null
                                                },

                                                {
                                                    text: item[1],
                                                    alignment: 'right',
                                                    bold: isLast,
                                                    fillColor: isLast ? '#F1EDF5' : null
                                                }

                                            ];
                                        })
                                    ]
                                },

                                layout: {

                                    hLineWidth: function () {
                                        return 0.5;
                                    },

                                    vLineWidth: function () {
                                        return 0.5;
                                    },

                                    hLineColor: function () {
                                        return '#D5DCE5';
                                    },

                                    vLineColor: function () {
                                        return '#D5DCE5';
                                    },

                                    paddingLeft: function () {
                                        return 7;
                                    },

                                    paddingRight: function () {
                                        return 7;
                                    },

                                    paddingTop: function () {
                                        return 5;
                                    },

                                    paddingBottom: function () {
                                        return 5;
                                    }
                                },

                                fontSize: 7,

                                margin: [0, 0, 0, 10]
                            });

                            // =====================================================
                            // 13. FOOTER HALAMAN
                            // =====================================================

                            doc.footer = function (currentPage, pageCount) {

                                return {

                                    columns: [

                                        {
                                            text: 'Laporan Cicilan',

                                            alignment: 'left',

                                            fontSize: 7,

                                            color: '#778398',

                                            margin: [12, 8, 0, 0]
                                        },

                                        {
                                            text: 'Halaman ' + currentPage + ' dari ' + pageCount,

                                            alignment: 'right',

                                            fontSize: 7,

                                            color: '#778398',

                                            margin: [0, 8, 12, 0]
                                        }

                                    ]
                                };
                            };
                        }
                    },

                    // =================================================
                    // PRINT
                    // =================================================

                    {
                        extend: 'print',

                        text: '<i class="bx bx-printer me-1"></i> Cetak',

                        className: 'btn btn-outline-secondary',

                        title: 'Laporan Cicilan',

                        exportOptions: {
                            columns: ':visible',

                            format: {
                                body: formatDocumentCell
                            }
                        },

                        footer: true,

                        customize: function (win) {

                            $(win.document.body).css(
                                'font-family',
                                'Arial, sans-serif'
                            );

                            $(win.document.body).find('h1').css({
                                'font-size': '18px',
                                'font-weight': '700',
                                'margin-bottom': '18px'
                            });

                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css({
                                    'font-size': '8px',
                                    'width': '100%',
                                    'border-collapse': 'collapse'
                                });

                            $(win.document.body).find('th, td').css({
                                'padding': '5px',
                                'border': '1px solid #ddd'
                            });
                        }
                    }
                ],

                // =====================================================
                // NOMOR URUT
                // =====================================================

                drawCallback: function () {

                    const api = this.api();
                    const pageInfo = api.page.info();

                    api.column(0, {
                        page: 'current'
                    }).nodes().each(function (cell, index) {

                        cell.innerHTML =
                            pageInfo.start + index + 1;
                    });
                }
            });

            // =========================================================
            // TAMPILAN TOMBOL
            // =========================================================

            $(tableSelector + '_wrapper .dt-buttons').addClass('mb-3');

            // =========================================================
            // VALIDASI TANGGAL
            // =========================================================

            $(document).on('change', '#tanggal_mulai', function () {

                const startDate = $(this).val();

                $('#tanggal_akhir').attr('min', startDate);

                const endDate = $('#tanggal_akhir').val();

                if (endDate && startDate && endDate < startDate) {
                    $('#tanggal_akhir').val(startDate);
                }
            });

        });
    </script>
@endpush
