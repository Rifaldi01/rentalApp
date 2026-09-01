@extends('layouts.master')

@section('title', strtoupper('Laporan Rental'))

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-0 text-uppercase">Laporan Cicilan Rental</h4>
        </div>
    </div>

    <hr>

    <div class="card table-timbang">
        <div class="card-header">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert border-0 border-start border-5 border-danger alert-dismissible fade show py-2">
                        <div class="d-flex align-items-center">
                            <div class="font-35 text-danger"><i class="bx bxs-message-square-x"></i></div>
                            <div class="ms-3">
                                <h6 class="mb-0 text-danger">Error</h6>
                                <div>{{ $error }}</div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endforeach
            @endif

            <div class="row">
                <form action="{{ route('admin.report.filtercicilan') }}" method="GET">
                    <div class="row">
                        <div class="col-5 ms-2 mt-2">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" required>
                        </div>

                        <div class="col-6 mt-2">
                            <label class="form-label">Tanggal Berakhir</label>
                            <input type="date" class="form-control" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" required>
                        </div>
                    </div>

                    <div class="col-md-1 pt-4 float-end me-5">
                        <button type="submit" class="btn btn-success">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="table-report-cicilan" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th>Tgl Inv</th>
                        <th>Invoice</th>
                        <th>Tgl Bayar</th>
                        <th>Pelanggan</th>
                        <th>Item</th>
                        <th>No Seri</th>
                        <th>Tgl Mulai</th>
                        <th>Tgl Selesai</th>
                        <th>Total <br>Inv</th>
                        <th>PPN</th>
                        <th>PPH</th>
                        <th>Fee</th>
                        <th>Diskon</th>
                        <th>Total</th>
                        <th>Ung <br>Masuk</th>
                        <th>Sisa <br>Bayar</th>
                        <th>Ket. (Nama Bank)</th>
                        <th>Penerima</th>
                        <th class="text-center">Status</th>
                    </tr>
                    </thead>

                    <tbody>
                    @php
                        $shownRentalFee = [];
                    @endphp

                    @foreach ($cicilan as $key => $datas)
                        @php
                            $rental = $datas->rental;
                            $customer = $rental?->cust;
                            $itemIds = json_decode($rental?->item_id ?? '[]', true);
                            $itemIds = is_array($itemIds) ? $itemIds : [];
                        @endphp

                        <tr>
                            {{-- NO --}}
                            <td class="text-center">{{ $key + 1 }}</td>

                            {{-- TGL INVOICE --}}
                            <td>{{ $rental?->tgl_inv ? formatId($rental->tgl_inv) : 'kosong' }}</td>

                            {{-- INVOICE --}}
                            <td>{{ $rental?->no_inv ?? '-' }}</td>

                            {{-- TGL BAYAR --}}
                            <td>{{ $datas->date_pay ? formatId($datas->date_pay) : '-' }}</td>

                            {{-- CUSTOMER --}}
                            <td>{{ $customer?->name ?? '-' }}</td>

                            {{-- ITEM --}}
                            <td>
                                @if (!empty($itemIds))
                                    @foreach ($itemIds as $itemId)
                                        @php
                                            $item = \App\Models\Item::find($itemId);
                                        @endphp

                                        <li>{{ $item?->name ?? 'Item not found' }}</li>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>

                            {{-- NO SERI --}}
                            <td>
                                @if (!empty($itemIds))
                                    @foreach ($itemIds as $itemId)
                                        @php
                                            $item = \App\Models\Item::find($itemId);
                                        @endphp

                                        <li>{{ $item?->no_seri ?? 'Item not found' }}</li>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>

                            {{-- TGL MULAI --}}
                            <td>{{ $rental?->date_start ? formatId($rental->date_start) : '-' }}</td>

                            {{-- TGL SELESAI --}}
                            <td>{{ $rental?->date_end ? formatId($rental->date_end) : '-' }}</td>

                            {{-- TOTAL INVOICE --}}
                            <td>{{ formatRupiah($rental?->total_invoice ?? 0) }}</td>

                            {{-- PPN --}}
                            <td>{{ formatRupiah($rental?->ppn ?? 0) }}</td>

                            {{-- PPH --}}
                            <td>{{ formatRupiah($rental?->pph ?? 0) }}</td>

                            {{-- FEE --}}
                            <td>
                                @if ($rental && !in_array($datas->rental_id, $shownRentalFee))
                                    {{ formatRupiah($rental->fee ?? 0) }}
                                    @php
                                        $shownRentalFee[] = $datas->rental_id;
                                    @endphp
                                @else
                                    -
                                @endif
                            </td>

                            {{-- DISKON --}}
                            <td>{{ formatRupiah($rental?->diskon ?? 0) }}</td>

                            {{-- TOTAL --}}
                            <td>{{ formatRupiah($total[$datas->id] ?? 0) }}</td>

                            {{-- UANG MASUK --}}
                            <td>{{ formatRupiah($datas->pay_debts ?? 0) }}</td>

                            {{-- SISA BAYAR --}}
                            <td>
                                @if ($rental?->nominal_out !== null)
                                    {{ formatRupiah($rental->nominal_out) }}
                                @else
                                    {{ formatRupiah($sisa[$datas->id] ?? 0) }}
                                @endif
                            </td>

                            {{-- KETERANGAN / BANK --}}
                            <td>
                                @if ($datas->bank_id)
                                    {{ $datas->bank?->name ?? '-' }}
                                @else
                                    {{ $datas->description ?? '-' }}
                                @endif
                            </td>

                            {{-- PENERIMA --}}
                            <td>{{ $datas->penerima ?? '-' }}</td>

                            {{-- STATUS --}}
                            <td class="text-center">
                                @if ($rental?->status == 1)
                                    <span class="badge bg-success">Rent</span>
                                @elseif ($rental?->status == 0)
                                    <span class="badge bg-secondary">Finished</span>
                                @elseif ($rental?->status == 2)
                                    <span class="badge bg-danger">Problem</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                    <tfoot>
                    <tr>
                        @for ($i = 0; $i < 16; $i++)
                            <td>-</td>
                        @endfor
                        <th class="border"><strong>Total Uang Masuk</strong></th>
                        <th class="border">{{ formatRupiah($uangmasuk ?? 0) }},-</th>
                        <td>-</td>
                        <td>-</td>
                    </tr>

                    <tr>
                        @for ($i = 0; $i < 16; $i++)
                            <td>-</td>
                        @endfor
                        <th class="border"><strong>Total Diskon</strong></th>
                        <th class="border">{{ formatRupiah($diskon ?? 0) }},-</th>
                        <td>-</td>
                        <td>-</td>
                    </tr>

                    <tr>
                        @for ($i = 0; $i < 16; $i++)
                            <td>-</td>
                        @endfor
                        <th class="border"><strong>Total Fee</strong></th>
                        <th class="border">{{ formatRupiah($totalfee ?? 0) }},-</th>
                        <td>-</td>
                        <td>-</td>
                    </tr>

                    <tr>
                        @for ($i = 0; $i < 16; $i++)
                            <td>-</td>
                        @endfor
                        <th class="border"><strong>Total Bersih</strong></th>
                        <th class="border">{{ formatRupiah($totalbersih ?? 0) }},-</th>
                        <td>-</td>
                        <td>-</td>
                    </tr>

                    <tr>
                        @for ($i = 0; $i < 16; $i++)
                            <td>-</td>
                        @endfor
                        <th class="border"><strong>Total Sisa Bayar</strong></th>
                        <th class="border">{{ formatRupiah($sisabayar ?? 0) }},-</th>
                        <td>-</td>
                        <td>-</td>
                    </tr>

                    <tr>
                        @for ($i = 0; $i < 16; $i++)
                            <td>-</td>
                        @endfor
                        <th class="border"><strong>Total PPN</strong></th>
                        <th class="border">{{ formatRupiah($totalppn ?? 0) }},-</th>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    </tfoot>
                </table>
            </div>

            <div class="card-footer">
                <table>
                    <tr>
                        <th><h5 class="mb-0 text-uppercase">Total Uang Masuk</h5></th>
                        <td><h5>:</h5></td>
                        <td><h5 class="ms-3">{{ formatRupiah($uangmasuk ?? 0) }},-</h5></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <style>
        table.dataTable {
            font-size: 13px;
        }

        table.dataTable td {
            padding: 3px;
        }

        table.dataTable th {
            white-space: nowrap;
        }
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function () {
            const table = $('#table-report-cicilan').DataTable({
                lengthChange: false,
                order: [[1, 'asc']],
                pageLength: 25,
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Excel',
                        title: function () {
                            const currentDate = new Date();
                            const day = String(currentDate.getDate()).padStart(2, '0');
                            const month = String(currentDate.getMonth() + 1).padStart(2, '0');
                            const year = String(currentDate.getFullYear()).slice(-2);

                            return 'Laporan Pembayaran Tanggal ' + `${day}/${month}/${year}`;
                        },
                        exportOptions: {
                            columns: ':visible',
                            footer: true,
                            format: {
                                body: function (data) {
                                    if (data === null || data === undefined) return '';

                                    return String(data)
                                        .replace(/\./g, '')
                                        .replace(/<li>/g, '')
                                        .replace(/<\/li>/g, '\n')
                                        .replace(/<br\s*\/?>/g, '\n')
                                        .replace(/<\/?[^>]+(>|$)/g, '');
                                }
                            }
                        },
                        customize: function (xlsx) {
                            const sheet = xlsx.xl.worksheets['sheet1.xml'];
                            const rows = $('row', sheet);
                            const tfoot = $('#table-report-cicilan tfoot');
                            let tfootRows = '';

                            tfoot.find('tr').each(function () {
                                let trow = '<row>';

                                $(this).find('th, td').each(function () {
                                    const cellText = $(this).text().trim();
                                    const cell = `<c t="inlineStr"><is><t>${cellText}</t></is></c>`;

                                    trow += cell;
                                });

                                trow += '</row>';
                                tfootRows += trow;
                            });

                            rows.last().after(tfootRows);
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        filename: 'Laporan_Rental',
                        exportOptions: {
                            stripHtml: false
                        },
                        customize: function (doc) {
                            doc.pageSize = {
                                width: 880,
                                height: 595
                            };

                            doc.pageOrientation = 'landscape';
                            doc.pageMargins = [20, 20, 20, 20];

                            const allData = table.data().toArray();
                            const headers = [];

                            $('#table-report-cicilan thead th').each(function () {
                                headers.push({
                                    text: $(this).text().trim(),
                                    style: 'tableHeader'
                                });
                            });

                            const tableBody = [headers];

                            allData.forEach(function (rowData) {
                                const row = [];

                                rowData.forEach(function (cellData) {
                                    let cleanedText = cellData ?? '';

                                    cleanedText = String(cleanedText)
                                        .replace(/<li>/g, '')
                                        .replace(/<\/li>/g, '\n')
                                        .replace(/<br\s*\/?>/g, '\n')
                                        .replace(/<\/?[^>]+(>|$)/g, '');

                                    row.push({
                                        text: cleanedText.trim(),
                                        style: 'tableCell'
                                    });
                                });

                                tableBody.push(row);
                            });

                            const tfoot = $('#table-report-cicilan tfoot').clone();

                            if (tfoot.length) {
                                tfoot.find('tr').each(function () {
                                    const footerRow = [];

                                    $(this).find('th, td').each(function () {
                                        footerRow.push({
                                            text: $(this).text().trim(),
                                            style: 'tableCell'
                                        });
                                    });

                                    while (footerRow.length < headers.length) {
                                        footerRow.push({
                                            text: '',
                                            style: 'tableCell'
                                        });
                                    }

                                    tableBody.push(footerRow);
                                });
                            }

                            doc.content = [
                                {
                                    table: {
                                        headerRows: 1,
                                        widths: Array(headers.length).fill('auto'),
                                        body: tableBody
                                    },
                                    layout: 'lightHorizontalLines'
                                }
                            ];

                            doc.styles.tableHeader = {
                                bold: true,
                                fontSize: 8,
                                color: 'black',
                                fillColor: '#f2f2f2',
                                alignment: 'center'
                            };

                            doc.styles.tableCell = {
                                fontSize: 7
                            };
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            stripHtml: false,
                            tfoot: true
                        },
                        customize: function (win) {
                            $(win.document.body)
                                .find('table')
                                .addClass('compact')
                                .css('font-size', '10px');

                            const tfoot = $('#table-report-cicilan tfoot').clone();

                            $(win.document.body).find('table').append(tfoot);
                        }
                    }
                ]
            });

            table.buttons().container().appendTo('#table-report-cicilan_wrapper .col-md-6:eq(0)');

            table.on('order.dt search.dt', function () {
                let i = 1;

                table.cells(null, 0, {
                    search: 'applied',
                    order: 'applied'
                }).every(function () {
                    this.data(i++);
                });
            }).draw();
        });
    </script>
@endpush
