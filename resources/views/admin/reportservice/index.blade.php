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
                                Start Date
                            </label>
                            <input type="date" class="form-control" name="start_date"  required>
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">
                                End Date
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
                            <th>Name</th>
                            <th>Item</th>
                            <th>No Seri</th>
                            <th>Date Service</th>
                            <th>Biaya Ganti</th>
                            <th>Nominal <br>In</th>
                            <th>Nominal <br>Outsid</th>
                            <th>Fee/ <br>Diskon</th>
                            <th>Ongkir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report as $key => $data)
                            <tr>
                                <td data-index="{{ $key +1 }}">{{$key +1}}</td>
                                <td>{{$data->name}}</td>
                                <td>{{$data->item}}</td>
                                <td>{{$data->no_seri}}</td>
                                <td>{{formatId($data->date_service)}}</td>
                                <td>{{formatRupiah($data->biaya_ganti)}}</td>
                                <td>{{formatRupiah($data->nominal_in)}}</td>
                                <td>{{formatRupiah($data->nominal_out)}}</td>
                                <td>{{formatRupiah($data->diskon)}}</td>
                                <td>{{formatRupiah($data->ongkir)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="border" colspan="2">Total Biaya Ganti</th>
                            <th class="border">{{formatRupiah($totalbiaya)}},-</th>
                        </tr>
                        <tr>
                            <th class="border" colspan="2">Total Nominal In</th>
                            <th class="border">{{formatRupiah($totalin)}},-</th>
                        </tr>
                        <tr>
                            <th class="border" colspan="2">Total Nominal Outside</th>
                            <th class="border">{{formatRupiah($totaloutside)}},-</th>
                        </tr>
                        <tr>
                            <th class="border" colspan="2">Total Fee/Diskon<</th>
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
    <div class="card">
        <div class="card-body">
            <div class="col">
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th> <h5 class="mb-0 text-uppercase">Total Biaya Ganti</h5></th>
                            <td><h5>:</h5></td>
                            <td><h5 class="ms-2">{{formatRupiah($totalbiaya)}},-</h5></td>
                        </tr>
                        <tr>
                            <th> <h5 class="mb-0 text-uppercase">Total Nominal In</h5></th>
                            <td><h5>:</h5></td>
                            <td><h5 class="ms-2">{{formatRupiah($totalin)}},-</h5></td>
                        </tr>
                        <tr>
                            <th> <h5 class="mb-0 text-uppercase">Total Nominal Outside</h5></th>
                            <td><h5>:</h5></td>
                            <td><h5 class="ms-2">{{formatRupiah($totaloutside)}},-</h5></td>
                        </tr>
                        <tr>
                            <th> <h5 class="mb-0 text-uppercase">Total Fee/Diskon</h5></th>
                            <td><h5>:</h5></td>
                            <td><h5 class="ms-2">{{formatRupiah($totaldiskon)}},-</h5></td>
                        </tr>
                        <tr>
                            <th> <h5 class="mb-0 text-uppercase">Total Ongkir</h5></th>
                            <td><h5>:</h5></td>
                            <td><h5 class="ms-2">{{formatRupiah($totalongkir)}},-</h5></td>
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
                        exportOptions: {
                            stripHtml: false,
                        },
                        customize: function(doc) {
                            doc.content = [];

                            doc.pageSize = {
                                width: 842,
                                height: 595
                            };
                            doc.pageOrientation = 'landscape';

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
                                    footerRow.push({ text: $(this).text(), style: 'tableCell' });
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
                                    fontSize: 12,
                                    fillColor: '#f2f2f2'
                                },
                                tableCell: {
                                    fontSize: 10
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
                            var bodyContent = $('#table-report tbody').clone();
                            $(win.document.body).find('table').append(bodyContent);
                            var footerContent = $('#table-report tfoot').clone();
                            $(win.document.body).find('table').append(footerContent);
                        }
                    }
                ]
            });

            table.buttons().container()
                .appendTo('#table-report_wrapper .col-md-6:eq(0)');
</script>
@endpush
