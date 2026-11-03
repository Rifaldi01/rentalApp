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

                </div>
            </div>
        </div>
        <di class="card-body">
            <div class="table-responsive">
                <table id="accessories" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th>Name</th>
                        <th>Stok All</th>
                        <th>Stok Available</th>
                        <th>Rental</th>
                        <th class="text-center" width="9%">Action</th>
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
                            <td>
                                <a href="{{ route('admin.acces.destroy', $data['id']) }}" data-confirm-delete="true" class="btn btn-danger btn-sm bx bx-trash" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"></a>
                                <button data-bs-toggle="modal" data-bs-target="#exampleVerticallycenteredModal{{ $data['id'] }}" class="btn btn-warning btn-sm float-end bx bx-edit ms-2" data-bs-placement="top" title="Edit"></button>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="exampleVerticallycenteredModal{{ $data['id'] }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Accessories</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.acces.update', $data['id']) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <label class="col-form-label">Name Accessories</label>
                                                    <input value="{{ $data['name'] }}" type="text" name="name" class="form-control" placeholder="Enter Accessories">
                                                    <label class="col-form-label mt-2">Stok</label>
                                                    <input type="number" value="{{ $data['stok'] }}" name="stok" class="form-control" placeholder="">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save<i class="bx bx-save"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End of Edit Modal -->
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </di>
    </div>

@endsection

@push('head')

@endpush
@push('js')
    <script>
        $(document).ready(function() {
            $('#submitBtn').click(function() {
                // Disable button dan ubah teksnya
                $(this).prop('disabled', true).text('Loading...');

                // Kirim form secara manual
                $('#myForm').submit();
            });
        });
    </script>
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
