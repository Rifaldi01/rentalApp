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
                            <input type="Tanggal" class="form-control" name="start_Tanggal"  required>
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">
                                End Tanggal
                            </label>
                            <input type="Tanggal" class="form-control" name="end_Tanggal"  required>
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
                            <th>Tanggal Service</th>
                            <th>Tanggal Selesai</th>
                            <th>Biaya Ganti</th>
                            <th>Uang <br>Masuk</th>
                            <th>Sisa <br>Bayar</th>
                            <th>Fee/ <br>Diskon</th>
                            <th>Ongkir</th>
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
                                <td>{{formatId($data->Tanggal_service)}}</td>
                                <td>
                                    @if($data->Tanggal_finis)
                                    {{formatId($data->Tanggal_finis)}}
                                    @else
                                    <div class="text-center">-</div>
                                    @endif
                                </td>
                                <td>{{formatRupiah($data->biaya_ganti)}}</td>
                                <td>{{formatRupiah($data->nominal_in)}}</td>
                                <td>{{formatRupiah($data->nominal_out)}}</td>
                                <td>{{formatRupiah($data->diskon)}}</td>
                                <td>{{formatRupiah($data->ongkir)}}</td>
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
                            <th class="border" colspan="2"> Total Ongkir</th>
                            <th class="border">{{formatRupiah($totalongkir)}},-</th>
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

@endpush

@push('js')
<script>
    $(document).ready(function() {
        var table = $('#table-report').DataTable();

        // Mengurutkan ulang nomor saat tabel diurutkan atau difilter
        table.on('order.dt search.dt', function() {
            let i = 1;
            table.cells(null, 0, { search: 'applied', order: 'applied' }).every(function(cell) {
                this.data(i++);
            });
        }).draw();
    });
    var table = $('#table-report').DataTable({
                lengthChange: false,
                buttons: [
                    {
                        extend: 'pdf',
                        filename: 'Laporan_Service',
                        exportOptions: {
                            stripHtml: false,
                        },
                        customize: function(doc) {
                            doc.content = [];

                            doc.pageSize = {
                                width: 842,
                                height: 595
                            };
                            doc.pageOrientation = 'auto';

                            doc.pageMargins = [20, 20, 20, 20];

                            var thead = $('#table-report thead').clone();
                            var headers = [];
                            thead.find('th').each(function() {
                                headers.push({ text: $(this).text(), style: 'tableHeader' });
                            });

                            var tableBody = [];
                            tableBody.push(headers);

                            $('#table-report tbody tr').each(function() {
                                var row = [];
                                $(this).find('td').each(function() {
                                    var cellText = $(this).text();
                                    if ($(this).find('ul').length > 0) {
                                        cellText = $(this).find('ul').html().replace(/<\/?li>/g, '');
                                        cellText = cellText.split('</li>').filter(item => item).map(item => ({ text: item.trim() }));
                                    }
                                    row.push({ text: cellText, style: 'tableCell' });
                                });
                                while (row.length < headers.length) {
                                    row.push({ text: '' });
                                }
                                tableBody.push(row);
                            });

                            var tfoot = $('#table-report tfoot').clone();
                            if (tfoot.length) {
                                var footerRow = [];
                                tfoot.find('th').each(function() {
                                    footerRow.push({ text: $(this).text(), style: 'tableFooter' });
                                });
                                while (footerRow.length < headers.length) {
                                    footerRow.push({ text: '' });
                                }
                                tableBody.push(footerRow);
                            }

                            doc.content.push({
                                table: {
                                    headerRows: 1,
                                    body: tableBody,
                                    widths: Array(headers.length).fill('*'),
                                    style: 'table'
                                },
                                layout: 'lightHorizontalLines'
                            });

                            doc.styles = {
                                table: {
                                    margin: [0, 5, 0, 15]
                                },
                                tableHeader: {
                                    bold: true,
                                    fontSize: 8,
                                    fillColor: '#f2f2f2'
                                },
                                tableFooter: {
                                    bold: true,
                                    fontSize: 8,
                                    fillColor: '#f2f2f2'
                                },
                                tableCell: {
                                    fontSize: 8
                                }
                            };
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            stripHtml: false,
                        },
                        customize: function(win) {
                            $(win.document.body).find('table').addClass('compact').css('font-size', '10px');
                            var tfoot = $('#table-report tfoot').clone();
                            $(win.document.body).find('table').append(tfoot);

                        }
                    }
                ]
            });

            table.buttons().container()
                .appendTo('#table-report_wrapper .col-md-6:eq(0)');
</script>
@endpush
