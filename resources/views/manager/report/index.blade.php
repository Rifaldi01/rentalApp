@extends('layouts.master')
@section('content')
<div class="card">
        <div class="card-body">
            <div class="col">
                <div class="row">
                    <div class="col-sm">
                        <h4 class="mb-0 text-uppercase">Cicilan Report
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr/>
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
            <div class="row">
                <form action="{{route('manager.report.filtercicilan')}}" method="GET">
                    <div class="row">
                        <div class="col-5 ms-2 mt-2">
                            <label class="form-label">
                                Start Date
                            </label>
                            <input type="date" class="form-control" name="tanggal_mulai" required>
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">
                                End Date
                            </label>
                            <input type="date" class="form-control" name="tanggal_akhir" required>
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
                        <th>Tgl Bayar</th>
                        <th>Pelanggan</th>
                        <th>Item</th>
                        <th>No Seri</th>
                        <th>Tgl Mulai</th>
                        <th>Tgl Selesai</th>
                        <th>Total <br>Inv</th>
                        <th width="">Ung <br>Masuk</th>
                        <th>Sisa <br>Bayar</th>
                        <th>Fee /<br>Discount</th>
                        <th>Total</th>
                        <th>Ket. (Nama Bank)</th>
                        <th>Penerima</th>
                        <th class="text-center">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($cicilan as $key => $datas)
                            <tr>
                                <td class="text-center"></td>
                                <td>{{$datas->rental->no_inv}}</td>
                                <td>{{formatId($datas->date_pay)}}</td>
                                <td>{{$datas->rental->cust->name}}</td>
                                <td>
                                    @php
                                        $itemIds = json_decode($datas->rental->item_id);
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
                                <td>
                                    @php
                                        $itemIds = json_decode($datas->rental->item_id);
                                    @endphp
                                    @if(is_array($itemIds))
                                        @foreach($itemIds as $itemId)
                                            @php
                                                $item = \App\Models\Item::find($itemId);
                                            @endphp
                                            <li>{{ $item ? $item->no_seri : 'Item not found' }}</li>
                                        @endforeach
                                    @else
                                        {{ $itemIds }}
                                    @endif
                                </td>
                                <td>{{formatId($datas->rental->date_start)}}</td>
                                <td>{{formatId($datas->rental->date_end)}}</td>
                                <td>
                                    @if($datas->rental->total_invoice)
                                        {{formatRupiah($datas->rental->total_invoice)}}
                                    @else
                                        0
                                    @endif
                                </td>
                                <td>
                                    {{formatRupiah($datas->pay_debts)}}
                                </td>
                                <td>
                                    {{formatRupiah($datas->rental->nominal_out)}}
                                </td>
                                <td>{{formatRupiah($datas->rental->diskon)}}</td>
                                <td>{{formatRupiah($total[$datas->id])}}</td>
                                <td>
                                @if($datas->bank_id)
                                    {{$datas->bank->name}}
                                @else
                                    {{$datas->description}}
                                @endif
                                </td>
                                <td>
                                    @if($datas->penerima)
                                    {{$datas->penerima}}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($datas->rental->status == 1)
                                        <span class="badge bg-success">Rent</span>
                                    @elseif($datas->rental->status == 0)
                                        <span class="badge bg-secondary">Finished</span>
                                    @elseif($datas->rental->status == 2)
                                        <span class="badge bg-danger">Problem</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="border" colspan="2"> Total Uang Masuk</th>
                            <th class="border" colspan="2">{{formatRupiah($uangmasuk)}},-</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer">
                    <table>
                        <tr>
                            <th> <h5 class="mb-0 text-uppercase">Total Uang Masuk</h5></th>
                            <td><h5>:</h5></td>
                            <td><h5 class="ms-3">{{formatRupiah($uangmasuk)}},-</h5></td>
                        </tr>
                        
                    </table>
            </div>
        </div>
        
    </div>
    <div class="card">
        <div class="card-body">
            <div class="col">
                <div class="row">
                    <div class="col-sm">
                        <h4 class="mb-0 text-uppercase">Rentals Report
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr/>
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
            <div class="row">
                <form action="{{route('manager.report.filter')}}" method="GET">
                    <div class="row">
                        <div class="col-5 ms-2 mt-2">
                            <label class="form-label">
                                Start Date
                            </label>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">
                                End Date
                            </label>
                            <input type="date" class="form-control" name="end_date" required>
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
                <table id="table-report" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th>Tanggal Inv</th>
                        <th>No Inv</th>
                        <th>Pelanggan</th>
                        <th>Item</th>
                        <th>No Seri</th>
                        <th>Tgl Mulai</th>
                        <th>Tgl Selesai</th>
                        <th>Total <br>Inv</th>
                        <th width="">Ung <br>Masuk</th>
                        <th>Sisa <br>Bayar</th>
                        <th>Fee /<br>Discount</th>
                        <th>Total</th>
                        <th>Ket. Byr</th>
                        <th>Penerima</th>
                        <th class="text-center">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($report as $key => $data)
                        <tr>
                            <td>{{$key +1}}</td>
                            <td>
                                {{formatId($data->tgl_inv)}}
                            </td>
                            <td>{{$data->no_inv}}</td>
                            <td>{{$data->cust->name}}</td>
                                <td>@php
                                        $itemIds = json_decode($data->item_id);
                                    @endphp
                                    @if(is_array($itemIds))
                                        @foreach($itemIds as $itemId)
                                            @php
                                                $item = \App\Models\Item::find($itemId);
                                            @endphp
                                            {{ $item ? $item->name : 'Item not found' }}<br>
                                        @endforeach
                                    @else
                                        {{ $itemIds }}
                                    @endif</td>
                            <td>@php
                                    $itemIds = json_decode($data->item_id);
                                @endphp
                                @if(is_array($itemIds))
                                    @foreach($itemIds as $itemId)
                                        @php
                                            $item = \App\Models\Item::find($itemId);
                                        @endphp
                                        {{ $item ? $item->no_seri : 'Item not found' }}<br>
                                    @endforeach
                                @else
                                    {{ $itemIds }}
                                @endif</td>
                            <td>
                                {{formatId($data->date_start)}}
                            </td>
                            <td>
                                {{formatId($data->date_end)}}
                            </td>
                            <td>
                                @if($data->total_invoice)
                                {{formatRupiah($data->total_invoice)}}
                                @else
                                {{formatRupiah($data->total_nominal)}}
                                @endif
                            </td>
                            <td>{{formatRupiah($data->nominal_in)}}</td>
                            <td>{{formatRupiah($data->nominal_out)}}</td>
                            <td>{{formatRupiah($data->diskon)}}</td>
                            <td>{{formatRupiah($data->total)}}</td>
                            <td>
                                @if($data->debt->isNotEmpty())
                                    @foreach($data->debt as $debt)
                                        @if($debt->bank)
                                            <li>{{$debt->date_pay}}, {{ $debt->bank->name }}</li>
                                        @else
                                        
                                        @endif
                                    @endforeach
                                @else
                                    {{$data->date_pays}}
                                @endif
                            </td>
                            <td>
                                @if($data->debt->isNotEmpty())
                                    @foreach($data->debt as $debt)
                                    <li>{{$debt->penerima}}</li>
                                    @endforeach
                                @else
                                    Tidak ada data
                                @endif
                            </td>
                            <td class="text-center">
                                @if($data->status == 1)
                                    <span class="badge bg-success">Rent</span>
                                @elseif($data->status == 0)
                                    <span class="badge bg-secondary">Finished</span>
                                @elseif($data->status == 2)
                                    <span class="badge bg-danger">Problem</span>
                                @endif
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="border" colspan="2"> Total Nominal In</th>
                            <th class="border">{{formatRupiah($totalin)}},-</th>
                        </tr>
                        <tr>
                            <th class="border" colspan="2"> Total Nominal Outside</th>
                            <th class="border">{{formatRupiah($totaloutside)}},-</th>
                        </tr>
                        <tr>
                            <th class="border" colspan="2"> Total Fee/Diskon</th>
                            <th class="border">{{formatRupiah($totaldiskon)}},-</th>
                        </tr>
                        <tr>
                            <th class="border" colspan="2">Grand Total</th>
                            <th class="border">{{formatRupiah($totalincome)}},-</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="table-responsive">
                    <table>
                        <tr>
                            <th> <h5 class="mb-0 text-uppercase">Total Nominal In</h5></th>
                            <td><h5>:</h5></td>
                            <td><h5 class="ms-3">{{formatRupiah($totalin)}},-</h5></td>
                        </tr>
                        <tr>
                            <th> <h5 class="mb-0 text-uppercase">Total Nominal Outside</h5></th>
                            <td><h5>:</h5></td>
                            <td><h5 class="ms-3">{{formatRupiah($totaloutside)}},-</h5></td>
                        </tr>
                        <tr>
                            <th> <h5 class="mb-0 text-uppercase">Total Fee/Diskon</h5></th>
                            <td><h5>:</h5></td>
                            <td><h5 class="ms-2">{{formatRupiah($totaldiskon)}},-</h5></td>
                        </tr>
                        <tr>
                            <th> <h5 class="mb-0 text-uppercase">Grand Total</h5></th>
                            <td><h5>:</h5></td>
                            <td><h5 class="ms-2">{{formatRupiah($totalincome)}},-</h5></td>
                        </tr>
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
            var table = $('#table-report').DataTable({
                lengthChange: false,
                buttons: [
                    {
                        extend: 'pdf',
                        filename: 'Laporan_Rental',
                        exportOptions: {
                            stripHtml: false,
                        },
                        customize: function (doc) {
                            // Set ukuran halaman PDF
                            doc.pageSize = {
                                width: 880,
                                height: 595,
                            };
                            doc.pageOrientation = 'landscape';
                            doc.pageMargins = [20, 20, 20, 20];

                            // Ambil seluruh data dari DataTables (termasuk yang tidak terlihat)
                            var allData = table.data().toArray();

                            // Header Tabel
                            var headers = [];
                            $('#table-report thead th').each(function () {
                                headers.push({ text: $(this).text(), style: 'tableHeader' });
                            });

                            // Isi Tabel
                            var tableBody = [];
                            tableBody.push(headers); // Tambahkan header ke body

                            allData.forEach(function (rowData) {
                                var row = [];
                                rowData.forEach(function (cellData) {
                                    // Hapus tag HTML seperti <li> dan <br>
                                    var cleanedText = cellData
                                        .replace(/<li>/g, '') // Hapus <li>
                                        .replace(/<\/li>/g, '\n') // Ganti </li> dengan baris baru
                                        .replace(/<br\s*\/?>/g, '\n') // Hapus <br> dan ganti dengan baris baru
                                        .replace(/<\/?[^>]+(>|$)/g, ''); // Hapus tag HTML lainnya
                                    row.push({ text: cleanedText.trim(), style: 'tableCell' });
                                });
                                tableBody.push(row);
                            });

                            // Footer Tabel (Jika Ada)
                            var tfoot = $('#table-report tfoot').clone();
                            if (tfoot.length) {
                                var footerRow = [];
                                tfoot.find('th').each(function () {
                                    footerRow.push({ text: $(this).text(), style: 'tableCell' });
                                });
                                while (footerRow.length < headers.length) {
                                    footerRow.push({ text: '' });
                                }
                                tableBody.push(footerRow);
                            }

                            // Tambahkan Tabel ke Dokumen
                            doc.content = [
                                {
                                    table: {
                                        headerRows: 1,
                                        widths: Array(headers.length).fill('auto'), // Perkecil kolom otomatis
                                        body: tableBody,
                                    },
                                    layout: 'lightHorizontalLines',
                                },
                            ];

                            // Styling
                            doc.styles.tableHeader = {
                                bold: true,
                                fontSize: 8, // Ukuran lebih kecil
                                color: 'black',
                                fillColor: '#f2f2f2',
                                alignment: 'center',
                            };
                            doc.styles.tableCell = {
                                fontSize: 7, // Ukuran lebih kecil
                            };
                        },
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            stripHtml: false,
                            tfoot: true,
                        },
                        customize: function (win) {
                            $(win.document.body)
                                .find('table')
                                .addClass('compact')
                                .css('font-size', '10px');
                            var tfoot = $('#table-report tfoot').clone();
                            $(win.document.body).find('table').append(tfoot);
                        },
                    },
                ],
            });

            // Tambahkan tombol ekspor ke container
            table
                .buttons()
                .container()
                .appendTo('#table-report_wrapper .col-md-6:eq(0)');
        });
        $(document).ready(function () {
            var table = $('#table-report-cicilan').DataTable({
                lengthChange: false,
                buttons: [
                    {
                        extend: 'pdf',
                        filename: 'Laporan_Rental_cicilan',
                        exportOptions: {
                            stripHtml: false,
                        },
                        customize: function (doc) {
                            // Set ukuran halaman PDF
                            doc.pageSize = {
                                width: 880,
                                height: 595,
                            };
                            doc.pageOrientation = 'landscape';
                            doc.pageMargins = [20, 20, 20, 20];

                            // Ambil seluruh data dari DataTables (termasuk yang tidak terlihat)
                            var allData = table.data().toArray();

                            // Header Tabel
                            var headers = [];
                            $('#table-report-cicilan thead th').each(function () {
                                headers.push({ text: $(this).text(), style: 'tableHeader' });
                            });

                            // Isi Tabel
                            var tableBody = [];
                            tableBody.push(headers); // Tambahkan header ke body

                            allData.forEach(function (rowData) {
                                var row = [];
                                rowData.forEach(function (cellData) {
                                    // Hapus tag HTML seperti <li> dan <br>
                                    var cleanedText = cellData
                                        .replace(/<li>/g, '') // Hapus <li>
                                        .replace(/<\/li>/g, '\n') // Ganti </li> dengan baris baru
                                        .replace(/<br\s*\/?>/g, '\n') // Hapus <br> dan ganti dengan baris baru
                                        .replace(/<\/?[^>]+(>|$)/g, ''); // Hapus tag HTML lainnya
                                    row.push({ text: cleanedText.trim(), style: 'tableCell' });
                                });
                                tableBody.push(row);
                            });

                            // Footer Tabel (Jika Ada)
                            var tfoot = $('#table-report-cicilan tfoot').clone();
                            if (tfoot.length) {
                                var footerRow = [];
                                tfoot.find('th').each(function () {
                                    footerRow.push({ text: $(this).text(), style: 'tableCell' });
                                });
                                while (footerRow.length < headers.length) {
                                    footerRow.push({ text: '' });
                                }
                                tableBody.push(footerRow);
                            }

                            // Tambahkan Tabel ke Dokumen
                            doc.content = [
                                {
                                    table: {
                                        headerRows: 1,
                                        widths: Array(headers.length).fill('auto'), // Perkecil kolom otomatis
                                        body: tableBody,
                                    },
                                    layout: 'lightHorizontalLines',
                                },
                            ];

                            // Styling
                            doc.styles.tableHeader = {
                                bold: true,
                                fontSize: 8, // Ukuran lebih kecil
                                color: 'black',
                                fillColor: '#f2f2f2',
                                alignment: 'center',
                            };
                            doc.styles.tableCell = {
                                fontSize: 7, // Ukuran lebih kecil
                            };
                        },
                    },
                    {
                        extend: 'print',
                        title: 'Laporan Cicilan Rental',
                        exportOptions: {
                            stripHtml: false,
                            tfoot: true,
                        },
                        customize: function (win) {
                            $(win.document.body)
                                .find('table')
                                .addClass('compact')
                                .css('font-size', '9.8px');
                            var tfoot = $('#table-report-cicilan tfoot').clone();
                            $(win.document.body).find('table').append(tfoot);
                        },
                    },
                ],
            });

            // Tambahkan tombol ekspor ke container
            table
                .buttons()
                .container()
                .appendTo('#table-report-cicilan_wrapper .col-md-6:eq(0)');
        });
    </script>

@endpush
