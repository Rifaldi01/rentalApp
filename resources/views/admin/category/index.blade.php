@extends('layouts.master')
@section('title', strtoupper('Daftar kategori alat'))
@section('content')
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-6">
                    <div class="container mt-3">
                    </div>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-dnd float-end me-3 mt-3 btn-sm shadow" data-bs-toggle="modal"
                            data-bs-target="#exampleVerticallycenteredModal"><i class="bx bx-plus"></i>
                    </button>
                </div>
            </div>
        </div>
        <di class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th>Nama</th>
                        <th class="text-center" width="9%">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach($cat as $key => $data)
                            <td>{{$key +1}}</td>
                            <td>{{$data->name}}</td>
                            <td class="text-center">
                                <a href="{{route('admin.cat.destroy', $data->id)}}" data-confirm-delete="true"
                                   class="btn btn-danger btn-sm bx bx-trash" title="Delete">
                                </a>
                                <button data-bs-toggle="modal"
                                        data-bs-target="#exampleVerticallycenteredModal{{$data->id}}"
                                        class="btn btn-warning btn-sm bx bx-edit ms-2"
                                        data-bs-placement="top" title="Edit">
                                </button>
                                <div class="modal fade" id="exampleVerticallycenteredModal{{$data->id}}" tabindex="-1"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Kategori</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <form action="{{route('admin.cat.update', $data->id)}}"
                                                  method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <label class="col-form-label">Nama Kategori</label>
                                                    <input value="{{$data->name}}" type="text" name="name"
                                                           class="form-control" placeholder="Enter Category">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tutup
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">Simpan<i
                                                            class="bx bx-save"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </di>
    </div>
    <div class="modal fade" id="exampleVerticallycenteredModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('admin.cat.store')}}" method="POST" id="myForm">
                    @csrf
                    <div class="modal-body">
                        <label class="col-form-label">Nama Kategori</label>
                        <input type="text" name="name" id="" class="form-control" placeholder="Enter Category">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Simpan<i class="bx bx-save"></i></button>
                    </div>
                </form>
            </div>
        </div>
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
@endpush
