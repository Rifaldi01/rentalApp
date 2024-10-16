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
                    </tr>
                </thead>
                <tbody>
                    @foreach($accessoriesData as $key => $data)
                    <tr>
                        <td data-index="{{ $key + 1 }}">{{ $key + 1 }}</td>
                        <td>{{ $data['name'] }}</td>
                        <td>{{ $data['stokAll'] }}</td>
                        <td>{{ $data['stok'] }}</td>
                        <td>{{ $data['rentedQty'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
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
        var table = $('#accessories').DataTable({
            lengthChange: false,
            buttons: [{
                extend: 'pdf',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                },
                customize: function(doc) {
                    doc.content[1].alignment = 'center';
                }
            }, {
                extend: 'print',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                },
                customize: function(win) {
                    $(win.document.body).find('table').addClass('table-center');
                }
            }]
        });

        table.buttons().container()
            .appendTo('#accessories_wrapper .col-md-6:eq(0)');
    });
    $(document).ready(function() {
        var table = $('#accessories').DataTable();

        // Mengurutkan ulang nomor saat tabel diurutkan atau difilter
        table.on('order.dt search.dt', function() {
            let i = 1;
            table.cells(null, 0, { search: 'applied', order: 'applied' }).every(function(cell) {
                this.data(i++);
            });
        }).draw();
    });
    </script>
@endpush
