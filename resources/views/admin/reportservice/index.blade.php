@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="col">
                <div class="row">
                    <div class="col-sm">
                        <h4 class="mb-0 text-uppercase">
                            Service Report
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr/>
    <div class="card table-timbang">
        <div class="card-head">
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
                <form action="{{route('admin.service.filter')}}" method="GET">
                    <div class="row">
                        <div class="col-5 ms-2 mt-2">
                            <label class="form-label">
                                Start Tanggal
                            </label>
                            <input type="date" class="form-control" name="start_date"  required>
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">
                                End Tanggal
                            </label>
                            <input type="date" class="form-control" name="end_date"  required>
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
                            <th>No Invoce</th>
                            <th>Pelanggan</th>
                            <th>Item</th>
                            <th>No Seri</th>
                            <th>Tgl. Service</th>
                            <th>Tgl. Selesai</th>
                            <th>Total Inv</th>
                            <th>Biaya Ganti</th>
                            <th>Fee/ <br>Diskon</th>
                            <th>Total <br>bersih</th>
                            <th>Uang <br>Masuk</th>
                            <th>Sisa <br>Bayar</th>
                            <th>Ket. Bayar</th>
                            <th>Penerima</th>
                            <th>Ket. Ganti</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report as $key => $data)
                            <tr>
                                <td data-index="{{ $key +1 }}">{{$key +1}}</td>
                                <td>{{$data->no_inv}}</td>
                                <td>{{$data->name}}</td>
                                <td>{{$data->item}}</td>
                                <td>{{$data->no_seri}}</td>
                                <td>{{formatId($data->date_service)}}</td>
                                <td>
                                    @if($data->date_finis)
                                    {{formatId($data->date_finis)}}
                                    @else
                                    <div class="text-center">-</div>
                                    @endif
                                </td>
                                <td>{{formatRupiah($data->total_invoice)}}</td>
                                <td>{{formatRupiah($data->biaya_ganti)}}</td>
                                <td>{{formatRupiah($data->diskon)}}</td>
                                <td>{{formatRupiah($data['nominal_in'] - $data['diskon'])}}</td>
                                <td>{{formatRupiah($data->nominal_in)}}</td>
                                <td>{{formatRupiah($data->nominal_out)}}</td>
                                <td>
                                    @if($data->debtService->isNotEmpty())
                                        @foreach($data->debtService as $debt)
                                            @if($debt->bank)
                                                <li>{{$debt->date_pay}}, {{ $debt->bank->name }}</li>
                                            @endif
                                            <li>{{$debt->date_pay}}, {{ $debt->description }}</li>
                                        @endforeach
                                    @else
                                        @foreach($data->debtService as $debt)
                                        {{$debt->date_pay}}, {{ $debt->description }}
                                        @endforeach
                                    @endif
                                    
                                </td>
                                <td>
                                    @if($data->debtService && $data->debtService->isNotEmpty())
                                        @foreach($data->debtService as $debt)
                                            <li>{{$debt->penerima}}</li>
                                        @endforeach
                                    @else
                                        Tidak ada data
                                    @endif

                                </td>
                                <td>{{$data->date_pays}}</td>
                                <td>
                                    @if($data->status == 0)
                                        <span class="badge bg-success">Service</span>
                                    @else($data->status == 1)
                                        <span class="badge bg-secondary">Finished</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="border" colspan="2">Total Biaya Ganti</th>
                            <th class="border">{{formatRupiah($totalbiaya)}},-</th>
                        </tr>
                        <tr>
                            <th class="border" colspan="2">Total Uang Masuk</th>
                            <th class="border">{{formatRupiah($totalin)}},-</th>
                        </tr>
                        <tr>
                            <th class="border" colspan="2">Total Belum Bayar</th>
                            <th class="border">{{formatRupiah($totaloutside)}},-</th>
                        </tr>
                        <tr>
                            <th class="border" colspan="2">Total Fee/Diskon</th>
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
    </div>
@endsection

@push('head')
    <style>
        table.dataTable {
            font-size: 12px /* Atur ukuran font */
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
                        filename: 'Laporan_Service',
                        exportOptions: {
                            stripHtml: false,
                        },
                        customize: function (doc) {
                            // Set ukuran halaman PDF
                            doc.pageSize = {
                                width: 780,
                                height: 595
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
                                fontSize: 10, // Ukuran lebih kecil
                                color: 'black',
                                fillColor: '#f2f2f2',
                                alignment: 'center',
                            };
                            doc.styles.tableCell = {
                                fontSize: 9, // Ukuran lebih kecil
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
    </script>

@endpush
