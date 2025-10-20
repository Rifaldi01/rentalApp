@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-6">
                    <div class="container mt-3">
                        <h4 class="text-uppercase">Accessories Maintenance</h4>
                    </div>
                </div>
                <div class="col-6">
                    <div class="container mt-3">
                        <button type="button" class="btn btn-dnd float-end me-3 btn-sm shadow"
                                data-bs-toggle="modal" data-bs-target="#exampleVerticallycenteredModal">
                            <i class="bx bx-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Name</th>
                        <th>Qty</th>
                        <th class="text-center">Detail</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($mainten as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $data->accessories->name }}</td>
                            <td>{{ $data->qty}}</td>
                            <td class="text-center">
                                <button data-bs-toggle="modal" data-bs-target="#exampleModal{{ $data->id }}"
                                        class="btn btn-dnd btn-sm lni lni-eye" title="Detail">
                                </button>
                                {{-- Modal Detail --}}
                                <div class="modal fade" id="exampleModal{{ $data->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Maintenance</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <table class="table table-bordered">
                                                <tr><th>Name</th><td>{{ $data->accessories->name }}</td></tr>
                                                <tr><th>No Seri</th><td>{{ $data->qty}}</td></tr>
                                                <tr><th>Descript</th><td>{{ $data->description }}</td></tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($data->status == 0)
                                    <form action="{{ route('employe.maintenace.finis', $data->id) }}" method="POST">
                                        @csrf
                                        <button onclick="return confirm('Maintenance Finished?');"
                                                type="submit" class="btn btn-success btn-sm bx bx-check">
                                        </button>
                                    </form>
                                    @elseif($data->status == 1)
                                        <span class="badge bg-secondary">Selesai</span>
                                    @endif

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div class="modal fade" id="exampleVerticallycenteredModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Accessories</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('employ.maintenace.accesStore') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="d-flex justify-content-end mb-3">
                            <button class="btn btn-dnd btn-sm add-field" type="button" id="add-field">
                                <i class="bx bx-plus"></i> Add Accessories
                            </button>
                        </div>

                        <div id="dynamic-fields">
                            {{-- Field awal --}}
                            <div class="row align-items-end mt-2 dynamic-row">
                                <div class="col-md-5">
                                    <label class="form-label">Accessories</label>
                                    <select name="accessories_id[]" class="form-select single-select-field" data-placeholder="--Select Accessories--">
                                        <option value="">--Select Accessories--</option>
                                        @foreach($accessories as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Qty</label>
                                    <input type="number" class="form-control" name="qty[]" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea class="form-control" name="description[]" required></textarea>
                                </div>

                                <div class="col-md-2 text-center">
                                    <button class="btn btn-danger remove-field mt-4" type="button">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            Save <i class="bx bx-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            // Inisialisasi select2 pertama
            function initSelect2() {
                $('#exampleVerticallycenteredModal .single-select-field').select2({
                    dropdownParent: $('#exampleVerticallycenteredModal'),
                   theme: 'bootstrap-5',
                    placeholder: '--Select Accessories--',
                    allowClear: true
                });
            }
            initSelect2();

            // Template row baru
            function newDynamicRow() {
                return `
        <div class="row align-items-end mt-2 dynamic-row">
            <div class="col-md-5">
                <label class="form-label">Accessories</label>
                <select name="accessories_id[]" class="form-select single-select-field" data-placeholder="--Select Accessories--">
                    <option value="">--Select Accessories--</option>
                    @foreach($accessories as $acc)
                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Qty</label>
                <input type="number" class="form-control" name="qty[]" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control" name="description[]" required></textarea>
            </div>
            <div class="col-md-2 text-center">
                <button class="btn btn-danger remove-field mt-4" type="button">
                    <i class="bx bx-trash"></i>
                </button>
            </div>
        </div>`;
            }

            // Tambah field baru
            $('#add-field').on('click', function() {
                const $newRow = $(newDynamicRow());
                $('#dynamic-fields').append($newRow);
                initSelect2();
            });

            // Hapus field
            $(document).on('click', '.remove-field', function() {
                $(this).closest('.dynamic-row').remove();
            });
        });
    </script>
@endpush
