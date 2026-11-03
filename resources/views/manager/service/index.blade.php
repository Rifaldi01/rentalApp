@extends('layouts.master')
@section('content')
    @if($service->isEmpty())
        <div class="card">
            <div class="card-head">
                <div class="row">
                    <div class="col-md-6">
                        <div class="container mt-3">
                            <h4 class="text-uppercase">Tidak Ada Item Diservice</h4>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Add Service"
                           href="{{route('manager.service.create')}}"
                           class="btn btn-dnd float-end me-3 mt-3 btn-sm shadow"><i
                                class="bx bx-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-head">
                <div class="row">
                    <div class="col-md-6">
                        <div class="container mt-3">
                            <h4 class="text-uppercase">List Service</h4>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Add Service"
                           href="{{route('manager.service.create')}}"
                           class="btn btn-dnd float-end me-3 mt-3 btn-sm shadow"><i
                                class="bx bx-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th width="2%">No</th>
                            <th>Invoice</th>
                            <th>Pelanggan</th>
                            <th>Nama Alat</th>
                            <th>No Seri</th>
                            <th>Type</th>
                            <th>Total Inv</th>
                            <th>Biaya Ganti</th>
                            <th>PPN</th>
                            <th>Uang Masuk</th>
                            <th>Sisa Bayar</th>
                            <th>Tgl Servis</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($service as $key => $data)
                            <tr>
                                <td>{{$key +1}}</td>
                                <td>{{$data->no_inv}}</td>
                                <td>
                                    @if($data->name)
                                        {{$data->name}}
                                    @else
                                        {{$data->cust->name}}
                                    @endif
                                </td>
                                <td>@foreach(explode(',', $data->item) as $item)
                                        <li>{{ trim($item) }}</li>
                                    @endforeach
                                </td>
                                <td>@foreach(explode(',', $data->no_seri) as $no_seri)
                                        <li>{{ trim($no_seri) }}</li>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach(explode(',', $data->type) as $type)
                                        <li>{{ trim($type) }} </li>
                                    @endforeach
                                </td>
                                <td>{{formatRupiah($data->total_invoice)}}</td>
                                <td>{{formatRupiah($data->biaya_ganti)}}</td>
                                <td>{{formatRupiah($data->ppn)}}</td>
                                <td>{{formatRupiah($data->nominal_in)}}</td>
                                <td class="text-center">
                                    @if($data->nominal_out == 0)
                                        <span class="badge bg-primary">Lunas</span>
                                    @else
                                        {{formatRupiah($data->nominal_out)}}
                                    @endif
                                </td>
                                <td>{{formatId($data->date_service)}}</td>
                                <td>
                                    @if($data->status == 0)
                                        <span class="badge bg-success">Service</span>
                                    @else($data->status == 1)
                                        <span class="badge bg-secondary">Finished</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('manager.service.edit', $data->id)}}" class="btn btn-primary lni lni-pencil btn-sm"
                                            data-bs-tool="tooltip" data-bs-placement="top" title="Detail"
                                            ></a>
                                    <button type="button"
                                            class="btn btn-danger lni lni-trash btn-sm"
                                            onclick="confirmDelete({{ $data->id }})"
                                            title="Hapus">
                                    </button>

                                    <form id="delete-form-{{ $data->id }}"
                                          action="{{ route('manager.service.destroy', $data->id) }}"
                                          method="POST"
                                          style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                @if($data->status == 0)
                                        <button class="btn btn-warning lni lni-eye btn-sm" data-bs-toggle="modal"
                                                data-bs-tool="tooltip" data-bs-placement="top" title="Detail"
                                                data-bs-target="#exampleLargeModal{{$data->id}}"></button>
                                        <div class="modal fade" id="exampleLargeModal{{$data->id}}" tabindex="-1"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Detail Service</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive">
                                                            <table id="" class="table table-bordered">
                                                                <tr>
                                                                    <th class="bg-primary text-center" colspan="4">
                                                                        CUSTOMER
                                                                    </th>
                                                                </tr>
                                                                <tr>
                                                                    <th colspan="2" width="10%">Pelanggan</th>
                                                                    <td colspan="2" class="">{{$data->name}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-center bg-primary" colspan="4">
                                                                        ITEM
                                                                    </th>
                                                                </tr>
                                                                <tr>
                                                                    <th colspan="2">
                                                                        <div class="text-center">Name Item</div>
                                                                    </th>
                                                                    <th>
                                                                        <div class="text-center">No Seri</div>
                                                                    </th>
                                                                    <th>
                                                                        <div class="text-center">Type</div>
                                                                    </th>
                                                                </tr>
                                                                <tr>

                                                                    <td colspan="2">
                                                                        <div class="text-center">{{$data->item}}</div>
                                                                    </td>
                                                                    <td>
                                                                        <div
                                                                            class="text-center">{{$data->no_seri}}</div>
                                                                    </td>
                                                                    <td>
                                                                        <div
                                                                            class="text-center">{{$data->type}}</div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>
                                                                        <div class="float-start">Jenis Service</div>
                                                                    </th>
                                                                    <td colspan="3">
                                                                        <div
                                                                            class="float-start">{{($data->jenis_service)}}</div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th colspan="4" class="bg-primary">
                                                                        <div class="text-center ">DATE</div>
                                                                    </th>
                                                                </tr>
                                                                <tr>
                                                                    <th colspan="2" class="text-center">Date Service
                                                                    </th>
                                                                    <th colspan="2" class="text-center">Date Finish</th>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2"
                                                                        class="text-center">{{dateId($data->date_service)}}</td>
                                                                    <td colspan="2" class="text-center">
                                                                        @if($data->date_finis)
                                                                            {{dateId($data->date_finis)}}
                                                                        @else
                                                                            <i class="text-danger">Service Belum
                                                                                Selesai</i>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>
                                                                        <div class="float-start">Descript</div>
                                                                    </th>
                                                                    <td colspan="3">
                                                                        <div
                                                                            class="float-start">{{$data->descript}}</div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>
                                                                        <div class="float-start">Status</div>
                                                                    </th>
                                                                    <td colspan="3">
                                                                        @if($data->status == 0)
                                                                            <span
                                                                                class="badge bg-success float-start">Service</span>
                                                                        @else
                                                                            <span
                                                                                class="badge bg-secondary float-start">Finished</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table id="" class="table table-bordered">
                                                                <tr>
                                                                    <th colspan="6" class="bg-success text-center">
                                                                        PRICE
                                                                    </th>
                                                                </tr>
                                                                <tr>
                                                                    <th>Total Invoice</th>
                                                                    <th>Uang Masuk</th>
                                                                    <th>Sisa Bayar</th>
                                                                    <th>Biaya Ganti</th>
                                                                    <th>Ongkir</th>
                                                                    <th> Diskon</th>
                                                                </tr>
                                                                <tr>
                                                                    <td>{{formatRupiah($data->total_invoice)}},-</td>
                                                                    <td>{{formatRupiah($data->nominal_in)}},-</td>
                                                                    <td>{{formatRupiah($data->nominal_out)}},-</td>
                                                                    <td>{{formatRupiah($data->biaya_ganti)}},-</td>
                                                                    <td>{{formatRupiah($data->ongkir)}},-</td>
                                                                    <td>{{formatRupiah($data->diskon)}},-</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">
                                                            Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($data->nominal_out == 0)
                                            <button class="btn btn-success btn-sm lni lni-checkmark"
                                                    data-bs-toggle="modal"
                                                    data-bs-tool="tooltip" data-bs-placement="top" title="Finis"
                                                    data-bs-target="#exampleVerticallycenteredModal{{$data->id}}"></button>
                                            <div class="modal fade" id="exampleVerticallycenteredModal{{$data->id}}"
                                                 tabindex="-1"
                                                 aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Date Finsished</h5>
                                                            <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{route('manager.service.update', $data->id)}}"
                                                              method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <label class="col-form-label">Biaya Ganti</label>
                                                                <input type="text"
                                                                       class="form-control " name="biaya_ganti"
                                                                       placeholder="Enter Date"
                                                                       value="{{$data->biaya_ganti}}">
                                                                <label class="col-form-label">Date Finish</label>
                                                                <input type="text"
                                                                       class="form-control datepicker" name="date_finis"
                                                                       placeholder="Enter Date">
                                                                <label class="col-form-label">Deskripsi</label>
                                                                <textarea type="text"
                                                                          class="form-control" name="descript"
                                                                          placeholder="Enter Date">{{$data->descript}}</textarea>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">Save<i
                                                                        class="bx bx-save"></i></button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($data->nominal_out != 0)
                                            <button class="btn btn-warning lni lni-dollar btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#examplemodal{{$data->id}}" data-bs-tool="tooltip"
                                                    data-bs-placement="top" title="Bayar">
                                            </button>
                                            <div class="modal fade" id="examplemodal{{$data->id}}" tabindex="-1"
                                                 aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Pembayaran</h5>
                                                            <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{route('manager.service.bayar', $data->id)}}"
                                                              method="POST" id="myForm">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="row mb-3">
                                                                    <label for="input42"
                                                                           class="col-sm-3 col-form-label"><i
                                                                            class="text-danger">*</i> Uang Masuk</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="position-relative input-icon">
                                                                            <input type="hidden"
                                                                                   id="nominal_in_value_{{$data->id}}"
                                                                                   value="{{ $data->nominal_in }}">
                                                                            <input type="text" class="form-control"
                                                                                   id="nominal_in_{{$data->id}}"
                                                                                   name="nominal_in"
                                                                                   value="{{ formatRupiah($data->nominal_in) }}"
                                                                                   readonly>
                                                                            <span
                                                                                class="position-absolute top-50 translate-middle-y"><i
                                                                                    class='bx bx-dollar'></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="input42"
                                                                           class="col-sm-3 col-form-label"><i
                                                                            class="text-danger">*</i> Pay Debts</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="position-relative input-icon">
                                                                            <input type="text" class="form-control"
                                                                                   name="pay_debts"
                                                                                   id="pay_debts_{{$data->id}}"
                                                                                   onkeyup="formatRupiah2(this)">
                                                                            <span
                                                                                class="position-absolute top-50 translate-middle-y"><i
                                                                                    class='bx bx-money'></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="input42"
                                                                           class="col-sm-3 col-form-label"><i
                                                                            class="text-danger">*</i> Date</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="position-relative input-icon">
                                                                            <input type="text"
                                                                                   class="form-control datepicker"
                                                                                   name="date_pay" id="input42">
                                                                            <span
                                                                                class="position-absolute top-50 translate-middle-y"><i
                                                                                    class='bx bx-calendar'></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="input42"
                                                                           class="col-sm-3 col-form-label">Biaya
                                                                        Ganti</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="position-relative input-icon">
                                                                            <input type="text" class="form-control"
                                                                                   name="biaya_ganti"
                                                                                   id="biaya_ganti_{{$data->id}}"
                                                                                   onkeyup="formatRupiah2(this)"
                                                                                   value="{{$data->biaya_ganti}}">
                                                                            <span
                                                                                class="position-absolute top-50 translate-middle-y"><i
                                                                                    class='bx bx-money'></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3" id="bankField_{{$data->id}}">
                                                                    <label for="input42"
                                                                           class="col-sm-3 col-form-label">Bank</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-text"><i
                                                                                    class="bx bx-credit-card"></i></div>
                                                                            <select class="form-select"
                                                                                    id="single-select-field"
                                                                                    name="bank_id"
                                                                                    data-placeholder="-- Nama Bank --">
                                                                                <option></option>
                                                                                @foreach($bank as $banks)
                                                                                    <option
                                                                                        value="{{$banks->id}}">{{$banks->name}}
                                                                                        ({{$banks->code}})
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3" id="penerimaField_{{$data->id}}">
                                                                    <label for="input42"
                                                                           class="col-sm-3 col-form-label">Penerima</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="position-relative input-icon">
                                                                            <input type="text" class="form-control"
                                                                                   name="penerima"
                                                                                   id="penerima_{{$data->id}}">
                                                                            <span
                                                                                class="position-absolute top-50 translate-middle-y"><i
                                                                                    class='bx bx-user'></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="input42"
                                                                           class="col-sm-3 col-form-label"><i
                                                                            class="text-danger"></i> </label>
                                                                    <div class="col-sm-9">
                                                                        <div class="position-relative input-icon">
                                                                            <input type="checkbox" class="form-check"
                                                                                   id="lainya_{{$data->id}}">
                                                                            <span
                                                                                class="position-absolute top-50 translate-middle-y ms-1"> Lainya</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3" id="descriptionField">
                                                                    <label for="input42"
                                                                           class="col-sm-3 col-form-label"></label>
                                                                    <div class="col-sm-9">
                                                                    <textarea id="description_{{$data->id}}" type="text"
                                                                              class="form-control" name="description"
                                                                              placeholder="Isi Lainya pembayaran melalui apa?"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary" id="bayarbutton">Save
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <form action="{{ route('manager.service.finis', $data->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="btn-sm btn btn-success lni lni-checkmark btn-finish mt-1"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Finished">

                                                </button>
                                            </form>
                                        @endif
                                        @if($data->total_invoice == 0 || $data->no_inv == 'proses')
                                            <button class="btn btn-dnd btn-sm lni lni-pencil" data-bs-toggle="modal"
                                                    data-bs-tool="tooltip" data-bs-placement="top" title="Edit Invoice"
                                                    data-bs-target="#editInvoice{{$data->id}}"></button>
                                            <div class="modal fade" id="editInvoice{{$data->id}}"
                                                 tabindex="-1"
                                                 aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Data</h5>
                                                            <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{route('manager.service.invoice', $data->id)}}"
                                                              method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <label class="col-form-label">No Invoice</label>
                                                                <input type="text" value="{{$data->no_inv}}"
                                                                       class="form-control" name="no_inv"
                                                                       placeholder="DND/INV/***/**">
                                                                <label class="col-form-label">Total Invoice</label>
                                                                <input type="text" onkeyup="formatRupiah2(this)"
                                                                       class="form-control " name="total_invoice"
                                                                       placeholder="Enter Date"
                                                                       value="{{$data->total_invoice}}">
                                                                <label class="col-form-label">Tanggal Invoice</label>
                                                                <input type="text" value="{{$data->tgl_inv}}"
                                                                       class="form-control datepicker" name="tgl_inv"
                                                                       placeholder="Enter Date">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">Save<i
                                                                        class="bx bx-save"></i></button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @else($data->status == 1)
                                        <button class="btn btn-warning lni lni-eye btn-sm" data-bs-toggle="modal"
                                                data-bs-tool="tooltip" data-bs-placement="top" title="Detail"
                                                data-bs-target="#exampleLargeModal{{$data->id}}"></button>
                                        <div class="modal fade" id="exampleLargeModal{{$data->id}}" tabindex="-1"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Detail Service</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="modal-body">
                                                            <div class="table-responsive">
                                                                <table id="" class="table table-bordered">
                                                                    <tr>
                                                                        <th class="bg-primary text-center" colspan="4">
                                                                            CUSTOMER
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th colspan="2" width="10%">Pelanggan</th>
                                                                        <td colspan="2" class="">{{$data->name}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-center bg-primary" colspan="4">
                                                                            ITEM
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th colspan="2">
                                                                            <div class="text-center">Name Item</div>
                                                                        </th>
                                                                        <th>
                                                                            <div class="text-center">No Seri</div>
                                                                        </th>
                                                                        <th>
                                                                            <div class="text-center">Type</div>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>

                                                                        <td colspan="2">
                                                                            <div
                                                                                class="text-center">{{$data->item}}</div>
                                                                        </td>
                                                                        <td>
                                                                            <div
                                                                                class="text-center">{{$data->no_seri}}</div>
                                                                        </td>
                                                                        <td>
                                                                            <div
                                                                                class="text-center">{{$data->type}}</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>
                                                                            <div class="float-start">Jenis Service</div>
                                                                        </th>
                                                                        <td colspan="3">
                                                                            <div
                                                                                class="float-start">{{$data->jenis_service}}</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th colspan="4" class="bg-primary">
                                                                            <div class="text-center ">DATE</div>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th colspan="2" class="text-center">Date Service
                                                                        </th>
                                                                        <th colspan="2" class="text-center">Date
                                                                            Finish
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2"
                                                                            class="text-center">{{dateId($data->date_service)}}</td>
                                                                        <td colspan="2" class="text-center">
                                                                            @if($data->date_finis)
                                                                                {{dateId($data->date_finis)}}
                                                                            @else
                                                                                <i class="text-danger">Service Belum
                                                                                    Selesai</i>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>
                                                                            <div class="float-start">Descript</div>
                                                                        </th>
                                                                        <td colspan="3">
                                                                            <div
                                                                                class="float-start">{{$data->descript}}</div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>
                                                                            <div class="float-start">Status</div>
                                                                        </th>
                                                                        <td colspan="3">
                                                                            @if($data->status == 0)
                                                                                <span
                                                                                    class="badge bg-success float-start">Service</span>
                                                                            @else
                                                                                <span
                                                                                    class="badge bg-secondary float-start">Finished</span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div class="table-responsive">
                                                                <table id="" class="table table-bordered">
                                                                    <tr>
                                                                        <th colspan="6" class="bg-success text-center">
                                                                            PRICE
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Total Invoice</th>
                                                                        <th>Uang Masuk</th>
                                                                        <th>Sisa Bayar</th>
                                                                        <th>Biaya Ganti</th>
                                                                        <th>Ongkir</th>
                                                                        <th> Diskon</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>{{formatRupiah($data->total_invoice)}},-
                                                                        </td>
                                                                        <td>{{formatRupiah($data->nominal_in)}},-</td>
                                                                        <td>{{formatRupiah($data->nominal_out)}},-</td>
                                                                        <td>{{formatRupiah($data->biaya_ganti)}},-</td>
                                                                        <td>{{formatRupiah($data->ongkir)}},-</td>
                                                                        <td>{{formatRupiah($data->diskon)}},-</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">
                                                            Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($data->nominal_out != 0)
                                            <button class="btn btn-warning lni lni-dollar btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#examplemodal{{$data->id}}" data-bs-tool="tooltip"
                                                    data-bs-placement="top" title="Bayar">
                                            </button>
                                            <div class="modal fade" id="examplemodal{{$data->id}}" tabindex="-1"
                                                 aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Pembayaran</h5>
                                                            <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{route('manager.service.bayar', $data->id)}}"
                                                              method="POST" id="myForm">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="row mb-3">
                                                                    <label for="input42"
                                                                           class="col-sm-3 col-form-label"><i
                                                                            class="text-danger">*</i> Uang Masuk</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="position-relative input-icon">
                                                                            <input type="hidden"
                                                                                   id="nominal_in_value_{{$data->id}}"
                                                                                   value="{{ $data->nominal_in }}">
                                                                            <input type="text" class="form-control"
                                                                                   id="nominal_in_{{$data->id}}"
                                                                                   name="nominal_in"
                                                                                   value="{{ formatRupiah($data->nominal_in) }}"
                                                                                   readonly>
                                                                            <span
                                                                                class="position-absolute top-50 translate-middle-y"><i
                                                                                    class='bx bx-dollar'></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="input42"
                                                                           class="col-sm-3 col-form-label"><i
                                                                            class="text-danger">*</i> Pay Debts</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="position-relative input-icon">
                                                                            <input type="text" class="form-control"
                                                                                   name="pay_debts"
                                                                                   id="pay_debts_{{$data->id}}"
                                                                                   onkeyup="formatRupiah2(this)">
                                                                            <span
                                                                                class="position-absolute top-50 translate-middle-y"><i
                                                                                    class='bx bx-money'></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="input42"
                                                                           class="col-sm-3 col-form-label"><i
                                                                            class="text-danger">*</i> Date</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="position-relative input-icon">
                                                                            <input type="text"
                                                                                   class="form-control datepicker"
                                                                                   name="date_pay" id="input42">
                                                                            <span
                                                                                class="position-absolute top-50 translate-middle-y"><i
                                                                                    class='bx bx-calendar'></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="input42"
                                                                           class="col-sm-3 col-form-label">Biaya
                                                                        Ganti</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="position-relative input-icon">
                                                                            <input type="text" class="form-control"
                                                                                   name="biaya_ganti"
                                                                                   id="biaya_ganti_{{$data->id}}"
                                                                                   onkeyup="formatRupiah2(this)"
                                                                                   value="{{$data->biaya_ganti}}">
                                                                            <span
                                                                                class="position-absolute top-50 translate-middle-y"><i
                                                                                    class='bx bx-money'></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3" id="bankField_{{$data->id}}">
                                                                    <label for="input42"
                                                                           class="col-sm-3 col-form-label">Bank</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-text"><i
                                                                                    class="bx bx-credit-card"></i></div>
                                                                            <select class="form-select"
                                                                                    id="single-select-field"
                                                                                    name="bank_id"
                                                                                    data-placeholder="-- Nama Bank --">
                                                                                <option></option>
                                                                                @foreach($bank as $banks)
                                                                                    <option
                                                                                        value="{{$banks->id}}">{{$banks->name}}
                                                                                        ({{$banks->code}})
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3" id="penerimaField_{{$data->id}}">
                                                                    <label for="input42"
                                                                           class="col-sm-3 col-form-label">Penerima</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="position-relative input-icon">
                                                                            <input type="text" class="form-control"
                                                                                   name="penerima"
                                                                                   id="penerima_{{$data->id}}">
                                                                            <span
                                                                                class="position-absolute top-50 translate-middle-y"><i
                                                                                    class='bx bx-user'></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="input42"
                                                                           class="col-sm-3 col-form-label"><i
                                                                            class="text-danger"></i> </label>
                                                                    <div class="col-sm-9">
                                                                        <div class="position-relative input-icon">
                                                                            <input type="checkbox" class="form-check"
                                                                                   id="lainya_{{$data->id}}">
                                                                            <span
                                                                                class="position-absolute top-50 translate-middle-y ms-1"> Lainya</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3" id="descriptionField">
                                                                    <label for="input42"
                                                                           class="col-sm-3 col-form-label"></label>
                                                                    <div class="col-sm-9">
                                                                    <textarea id="description_{{$data->id}}" type="text"
                                                                              class="form-control" name="description"
                                                                              placeholder="Isi Lainya pembayaran melalui apa?"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary" id="bayarbutton">Save
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if($data->total_invoice == 0 || $data->no_inv == 'proses')
                                            <button class="btn btn-dnd btn-sm lni lni-pencil" data-bs-toggle="modal"
                                                    data-bs-tool="tooltip" data-bs-placement="top" title="Edit Invoice"
                                                    data-bs-target="#editInvoice{{$data->id}}"></button>
                                            <div class="modal fade" id="editInvoice{{$data->id}}"
                                                 tabindex="-1"
                                                 aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Data</h5>
                                                            <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{route('manager.service.invoices', $data->id)}}"
                                                              method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <label class="col-form-label">No Invoice</label>
                                                                <input type="text" value="{{$data->no_inv}}"
                                                                       class="form-control" name="no_inv"
                                                                       placeholder="DND/INV/***/**">
                                                                <label class="col-form-label">Total Invoice</label>
                                                                <input type="text" onkeyup="formatRupiah2(this)"
                                                                       class="form-control " name="total_invoice"
                                                                       placeholder="Enter Date"
                                                                       value="{{$data->total_invoice}}">
                                                                <label class="col-form-label">Tanggal Invoice</label>
                                                                <input type="text" value=""
                                                                       class="form-control datepicker" name="tgl_inv"
                                                                       placeholder="Enter Date">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">Save<i
                                                                        class="bx bx-save"></i></button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('head')
    <link href="{{URL::to('assets/css/flatpickr.min.css')}}" rel="stylesheet"/>
@endpush
@push('js')
    <script src="{{URL::to('assets/js/flatpickr.min.js')}}"></script>

    <script>
         $(document).ready(function () {
            // Inisialisasi Select2 setelah modal dibuka
            $(document).on('shown.bs.modal', function (e) {
                let modal = $(e.target); // Modal yang sedang ditampilkan
                modal.find('#single-select-field').select2({
                    dropdownParent: modal, // Tetapkan parent dropdown ke modal yang aktif
                    placeholder: '-- Nama Bank --',
                    allowClear: true,
                    theme: 'bootstrap-5'
                });
            });
        });

        $(".datepicker").flatpickr();

        $(".time-picker").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "Y-m-d H:i",
        });

        $(".date-time").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });

        $(".date-format").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $(".date-range").flatpickr({
            mode: "range",
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $(".date-inline").flatpickr({
            inline: true,
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $(document).ready(function () {
            function calculateTotal(id) {
                let nominal_in = parseFloat($(`#nominal_in_value_${id}`).val()) || 0;
                let pay_debts = parseFloat($(`#pay_debts_${id}`).val().replace(/[^0-9]/g, '')) || 0;

                let total = nominal_in + pay_debts;
                $(`#nominal_in_${id}`).val('Rp. ' + total.toLocaleString('id-ID'));
            }

            $('[id^=pay_debts_]').on('input', function () {
                let id = $(this).attr('id').split('_')[2];
                calculateTotal(id);
            });

            $('[id^=nominal_in_value_]').each(function () {
                let id = $(this).attr('id').split('_')[2];
                calculateTotal(id);
            });
        });

        $(document).ready(function () {
            $('#bayarbutton').click(function (event) {
                // Nonaktifkan tombol dan ubah teksnya
                $(this).prop('disabled', true).text('Memuat...');

                $('#myForm').submit();
            });
        });

        function formatRupiah2(element) {
            let value = element.value.replace(/[^,\d]/g, '');
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            element.value = rupiah;

            function toggleValidation(id) {
            if ($(`#lainya_${id}`).is(':checked')) {
                // Jika checkbox lainya dicentang:
                $(`#description_${id}`).prop('required', true); // Description wajib diisi
                $(`#bankField_${id}`).hide(); // Sembunyikan bank field
                $(`#penerimaField_${id}`).hide(); // Sembunyikan bank field
                $(`#single-select-field_${id}`).prop('required', false); // Bank tidak wajib
                $(`#penerima_${id}`).prop('required', false); // Bank tidak wajib
            } else {
                // Jika checkbox lainya tidak dicentang:
                $(`#description_${id}`).prop('required', false); // Description tidak wajib
                $(`#bankField_${id}`).show(); // Tampilkan bank field
                $(`#penerimaField_${id}`).show(); // Tampilkan bank field
                $(`#single-select-field_${id}`).prop('required', true); // Bank wajib diisi
                $(`#penerima${id}`).prop('required', true); // Bank wajib diisi
            }
        }

        // Event listener untuk checkbox lainya
        $("[id^='lainya_']").on('change', function () {
            var id = $(this).attr('id').split('_')[1]; // Ambil ID dinamis
            toggleValidation(id);
        });

        // Inisialisasi validasi saat halaman dimuat
        $("[id^='lainya_']").each(function () {
            var id = $(this).attr('id').split('_')[1]; // Ambil ID dinamis
            toggleValidation(id);
        });

        }

        //delete
         function confirmDelete(id) {
             Swal.fire({
                 title: "Yakin ingin menghapus?",
                 text: "Data yang dihapus tidak dapat dikembalikan.",
                 icon: "warning",
                 showCancelButton: true,
                 confirmButtonColor: "#d33",
                 cancelButtonColor: "#6c757d",
                 confirmButtonText: "Ya, hapus",
                 cancelButtonText: "Batal"
             }).then((result) => {
                 if (result.isConfirmed) {
                     document.getElementById("delete-form-" + id).submit();
                 }
             });
         }

    </script>
@endpush
