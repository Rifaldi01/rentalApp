@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>QTY</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ins as $key => $data)
                        <tr>
                            <td>{{$key +1}}</td>
                            <td>{{formatId($data->created_at)}}</td>
                            <td>{{$data->accessories->name}}</td>
                            <td>{{$data->qty}}</td>
                            <td>{{$data->description}}</td>
                            <td><form action="{{ route('employe.accesin.destroy', $data['id']) }}"
                                      method="POST"
                                      class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button"
                                            class="btn btn-danger btn-sm bx bx-trash btn-delete"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Delete">
                                    </button>
                                </form></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('head') @endpush
@push('js')
    <script>
        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();

            let form = $(this).closest('.delete-form');

            Swal.fire({
                title: 'Delete Data?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
