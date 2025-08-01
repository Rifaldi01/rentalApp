@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-6 mt-3">
                    <div class="container">
                        <h4 class="text-uppercase">Pembayaran Belum Lunas</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="excel" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="4%">No</th>
                            <th class="text-center" width="5%">Invoice</th>
                            <th>Customer</th>
                            <th class="text-center" width="5%">Total Invoice</th>
                            <th class="text-center" width="5%">PPN</th>
                            <th class="text-center" width="5%">Uang Masuk</th>
                            <th class="text-center" width="5%">Sisa Bayar</th>
                            <th class="text-center" width="5%">Diskon</th>
                            <th class="text-center" width="5%">Total</th>
                            <th class="text-center" width="5%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rental as $key => $data)
                            <tr>
                                <td data-index="{{ $key + 1 }}">{{ $key + 1 }}</td>
                                <td>{{ $data->no_inv }}</td>
                                <td>{{ $data->cust->name }}</td>
                                <td class="text-center">
                                    @if ($data->total_invoice)
                                        {{ formatRupiah($data->total_invoice) }}
                                    @else
                                        Rp. 0
                                    @endif
                                </td>
                                <td>{{ formatRupiah($data->ppn) }}</td>
                                <td>{{ formatRupiah($data->nominal_in) }}</td>
                                <td>
                                    @if ($data->total_invoice == 0 || $data->total_invoice == null)
                                        {{ formatRupiah($data->nominal_out) }}
                                    @else
                                        {{ formatRupiah($data->total_invoice + $data->ppn - $data->nominal_in) }}
                                    @endif
                                </td>
                                <td>{{ formatRupiah($data->diskon) }}</td>
                                <td>{{ formatRupiah($total[$data->id]) }}</td>
                                <td class="text-center">
                                    @if ($data->total_invoice + $data->ppn == 0 || $data->total_invoice + $data->ppn == null)
                                        <button class="btn btn-dnd lni lni-pencil btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#edit{{ $data->id }}" data-bs-tool="tooltip"
                                            data-bs-placement="top" title="edit">
                                        </button>
                                        <div class="modal fade" id="edit{{ $data->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Total Invoice</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('admin.update.totalinv', $data->id) }}"
                                                        method="POST" id="myForm">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="row mb-3">
                                                                <label for="input42" class="col-sm-3 col-form-label"><i
                                                                        class="text-danger">*</i> Total Invoice</label>
                                                                <div class="col-sm-9">
                                                                    <div class="position-relative input-icon">
                                                                        <input type="text" class="form-control"
                                                                            name="total_invoice"
                                                                            value="{{ $data->total_invoice }}"
                                                                            onkeyup="formatRupiah2(this)">
                                                                        <span
                                                                            class="position-absolute top-50 translate-middle-y"><i
                                                                                class='bx bx-dollar'></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-primary" id="bayarbutton">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                    @endif
                                    <button class="btn btn-warning lni lni-dollar btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#examplemodal{{ $data->id }}" data-bs-tool="tooltip"
                                        data-bs-placement="top" title="Bayar">
                                    </button>
                                    <div class="modal fade" id="examplemodal{{ $data->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Pembayaran</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.pembayaran.bayar', $data->id) }}"
                                                    method="POST" id="myForm">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <label for="input42" class="col-sm-3 col-form-label"><i
                                                                    class="text-danger">*</i> Uang Masuk</label>
                                                            <div class="col-sm-9">
                                                                <div class="position-relative input-icon">
                                                                    <input type="hidden"
                                                                        id="nominal_in_value_{{ $data->id }}"
                                                                        value="{{ $data->nominal_in }}">
                                                                    <input type="text" class="form-control"
                                                                        id="nominal_in_{{ $data->id }}"
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
                                                            <label for="input42" class="col-sm-3 col-form-label"><i
                                                                    class="text-danger">*</i> Pay Debts</label>
                                                            <div class="col-sm-9">
                                                                <div class="position-relative input-icon">
                                                                    <input type="text" class="form-control"
                                                                        name="pay_debts"
                                                                        id="pay_debts_{{ $data->id }}"
                                                                        onkeyup="formatRupiah2(this)">
                                                                    <span
                                                                        class="position-absolute top-50 translate-middle-y"><i
                                                                            class='bx bx-money'></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="input42"
                                                                class="col-sm-3 col-form-label">Diskon</label>
                                                            <div class="col-sm-9">
                                                                <div class="position-relative input-icon">
                                                                    <input type="text" class="form-control"
                                                                        value="0" name="diskon"
                                                                        id="diskon_{{ $data->id }}"
                                                                        onkeyup="formatRupiah2(this)">
                                                                    <span
                                                                        class="position-absolute top-50 translate-middle-y"><i
                                                                            class='lni lni-tag'></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="input42" class="col-sm-3 col-form-label"><i
                                                                    class="text-danger">*</i> Date</label>
                                                            <div class="col-sm-9">
                                                                <div class="position-relative input-icon">
                                                                    <input type="text" class="form-control datepicker"
                                                                        name="date_pay" id="input42">
                                                                    <span
                                                                        class="position-absolute top-50 translate-middle-y"><i
                                                                            class='bx bx-calendar'></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3" id="bankField_{{ $data->id }}">
                                                            <label for="input42"
                                                                class="col-sm-3 col-form-label">Bank</label>
                                                            <div class="col-sm-9">
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-text"><i
                                                                            class="bx bx-credit-card"></i></div>
                                                                    <select class="form-select" id="single-select-field"
                                                                        name="bank_id" data-placeholder="-- Nama Bank --">
                                                                        <option></option>
                                                                        @foreach ($bank as $banks)
                                                                            <option value="{{ $banks->id }}">
                                                                                {{ $banks->name }}
                                                                                ({{ $banks->code }})
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3" id="penerimaField_{{ $data->id }}">
                                                            <label for="input42"
                                                                class="col-sm-3 col-form-label">Penerima</label>
                                                            <div class="col-sm-9">
                                                                <div class="position-relative input-icon">
                                                                    <input type="text" class="form-control"
                                                                        name="penerima"
                                                                        id="penerima_{{ $data->id }}">
                                                                    <span
                                                                        class="position-absolute top-50 translate-middle-y"><i
                                                                            class='bx bx-user'></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="input42" class="col-sm-3 col-form-label"><i
                                                                    class="text-danger"></i> </label>
                                                            <div class="col-sm-9">
                                                                <div class="position-relative input-icon">
                                                                    <input type="checkbox" class="form-check"
                                                                        id="lainya_{{ $data->id }}">
                                                                    <span
                                                                        class="position-absolute top-50 translate-middle-y ms-1">
                                                                        Lainya</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3" id="descriptionField">
                                                            <label for="input42" class="col-sm-3 col-form-label"></label>
                                                            <div class="col-sm-9">
                                                                <textarea id="description_{{ $data->id }}" type="text" class="form-control" name="description"
                                                                    placeholder="Isi Lainya pembayaran melalui apa?"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-primary" id="bayarbutton">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <form id="finisForm-{{ $data->id }}"
                                        action="{{ route('admin.pembayaran.finis', $data->id) }}" method="POST">
                                        @csrf
                                        <button type="button" class="btn-sm btn btn-success lni lni-checkmark mt-1"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Finished"
                                            onclick="confirmFinis('{{ $data->id }}')">
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    <tfoot>
                        <tr>
                            <th class="border" colspan="2"> Total Sisa Bayar</th>
                            <th class="border" colspan="2">{{ formatRupiah($hutang) }},-</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer">
                <table>
                    <tr>
                        <th>
                            <h5 class="mb-0 text-uppercase">Total Sisa Bayar</h5>
                        </th>
                        <td>
                            <h5>:</h5>
                        </td>
                        <td>
                            <h5 class="ms-3">{{ formatRupiah($hutang) }},-</h5>
                        </td>
                    </tr>

                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-head">
            <div class="row">
                <div class="col-6 mt-3">
                    <div class="container">
                        <h4 class="text-uppercase">Daftar Pembayaran</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <form action="{{ route('admin.pembayaran.filter') }}" method="GET">
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
            <div class="table-responsive">
                <table id="transaction" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="2%">No</th>
                            <th>Tgl Inv</th>
                            <th>Invoice</th>
                            <th>Tgl Bayar</th>
                            <th>Pelanggan</th>
                            <th>Item</th>
                            <th>No Seri</th>
                            <th>Tgl Mulai</th>
                            <th>Tgl Selesai</th>
                            <th>Total <br>Inv</th>
                            <th>Fee /<br>Discount</th>
                            <th>Total</th>
                            <th width="">Ung <br>Masuk</th>
                            <th>Sisa <br>Bayar</th>
                            <th>Ket. (Nama Bank)</th>
                            <th>Penerima</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($debt as $key => $data)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ formatId($data->rental->tgl_inv) }}</td>
                                <td>
                                    @if ($data->rental)
                                        {{ $data->rental->no_inv }}
                                    @else
                                    @endif
                                </td>
                                <td>{{ formatId($data->date_pay) }}</td>
                                <td>
                                    @if ($data->rental)
                                        {{ $data->rental->cust->name }}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $itemIds = json_decode($data->rental->item_id);
                                    @endphp
                                    @if (is_array($itemIds))
                                        @foreach ($itemIds as $itemId)
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
                                        $itemIds = json_decode($data->rental->item_id);
                                    @endphp
                                    @if (is_array($itemIds))
                                        @foreach ($itemIds as $itemId)
                                            @php
                                                $item = \App\Models\Item::find($itemId);
                                            @endphp
                                            <li>{{ $item ? $item->no_seri : 'Item not found' }}</li>
                                        @endforeach
                                    @else
                                        {{ $itemIds }}
                                    @endif
                                </td>
                                <td>{{ formatId($data->rental->date_start) }}</td>
                                <td>{{ formatId($data->rental->date_end) }}</td>
                                <td>
                                    @if ($data->rental->total_invoice)
                                        {{ formatRupiah($data->rental->total_invoice) }}
                                    @else
                                        0
                                    @endif
                                </td>
                                <td>{{ formatRupiah($data->rental->diskon) }}</td>
                                <td>{{ formatRupiah($totals[$data->id]) }}</td>
                                <td>{{ formatRupiah($data->pay_debts) }}</td>
                                <td>{{ formatRupiah($data->rental->nominal_out) }}</td>
                                <td>
                                    @if ($data->bank_id)
                                        {{ $data->bank->name }}
                                    @else
                                        {{ $data->description }}
                                    @endif
                                </td>
                                <td>
                                    @if ($data->penerima)
                                        {{ $data->penerima }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    <tfoot>
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <th class="border"><strong>Total Uang Masuk</strong></th>
                            <th class="border">{{ formatRupiah($uangmasuk) }},-</th>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <th class="border"><strong>Total Diskon</strong></th>
                            <th class="border">{{ formatRupiah($diskon) }},-</th>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <th class="border"><strong>Total Bersih</strong></th>
                            <th class="border">{{ formatRupiah($totalbersih) }},-</th>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <th class="border"><strong>Total Sisa Bayar</strong></th>
                            <th class="border">{{ formatRupiah($sisabayar) }},-</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer">
                <table>
                    <tr>
                        <th>
                            <h5 class="mb-0 text-uppercase"><strong>Total Uang Masuk</strong></h5>
                        </th>
                        <td>
                            <h5>:</h5>
                        </td>
                        <td>
                            <h5 class="ms-3">{{ formatRupiah($uangmasuk) }},-</h5>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <h5 class="mb-0 text-uppercase"><strong>Total Diskon</strong></h5>
                        </th>
                        <td>
                            <h5>:</h5>
                        </td>
                        <td>
                            <h5 class="ms-3">{{ formatRupiah($diskon) }},-</h5>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <h5 class="mb-0 text-uppercase"><strong>Total Bersih</strong></h5>
                        </th>
                        <td>
                            <h5>:</h5>
                        </td>
                        <td>
                            <h5 class="ms-3">{{ formatRupiah($totalbersih) }},-</h5>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <h5 class="mb-0 text-uppercase"><strong>Total Sisa Bayar</strong></h5>
                        </th>
                        <td>
                            <h5>:</h5>
                        </td>
                        <td>
                            <h5 class="ms-3">{{ formatRupiah($sisabayar) }},-</h5>
                        </td>
                    </tr>

                </table>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
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
    </script>
    <script>
        function confirmFinis(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Transaksi ini akan diselesaikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Selesaikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('finisForm-' + id).submit();
                }
            });
        }

        $(document).ready(function() {
            var table = $('#excel').DataTable({
                lengthChange: false,
                paginate: false,
                buttons: [{
                        extend: 'pdf',
                        exportOptions: {
                            stripHtml: false,
                        },
                        title: function() {
                            var currentDate = new Date();
                            var day = String(currentDate.getDate()).padStart(2,
                            '0'); // Mendapatkan tanggal dengan dua digit
                            var month = String(currentDate.getMonth() + 1).padStart(2,
                            '0'); // Mendapatkan bulan dengan dua digit
                            var year = String(currentDate.getFullYear()).slice(-
                            2); // Mendapatkan dua digit terakhir tahun
                            var formattedDate =
                            `${day}/${month}/${year}`; // Menggabungkan format tanggal/bulan/tahun
                            return 'Laporan Pembayaran Tanggal ' +
                            formattedDate; // Nama file sesuai tanggal
                        },

                        customize: function(doc) {
                            // Set ukuran halaman PDF

                            // Ambil seluruh data dari DataTables (termasuk yang tidak terlihat)
                            var allData = table.data().toArray();

                            // Header Tabel
                            var headers = [];
                            $('#excel thead th').each(function() {
                                headers.push({
                                    text: $(this).text(),
                                    style: 'tableHeader'
                                });
                            });

                            // Isi Tabel
                            var tableBody = [];
                            tableBody.push(headers); // Tambahkan header ke body

                            allData.forEach(function(rowData) {
                                var row = [];
                                rowData.forEach(function(cellData) {
                                    // Hapus tag HTML seperti <li> dan <br>
                                    var cleanedText = cellData
                                        .replace(/<li>/g, '') // Hapus <li>
                                        .replace(/<\/li>/g,
                                        '\n') // Ganti </li> dengan baris baru
                                        .replace(/<br\s*\/?>/g,
                                        '\n') // Hapus <br> dan ganti dengan baris baru
                                        .replace(/<\/?[^>]+(>|$)/g,
                                        ''); // Hapus tag HTML lainnya
                                    row.push({
                                        text: cleanedText.trim(),
                                        style: 'tableCell'
                                    });
                                });
                                tableBody.push(row);
                            });

                            // Footer Tabel (Jika Ada)
                            var tfoot = $('#excel tfoot').clone();
                            if (tfoot.length) {
                                var footerRow = [];
                                tfoot.find('th').each(function() {
                                    footerRow.push({
                                        text: $(this).text(),
                                        style: 'tableCell'
                                    });
                                });
                                while (footerRow.length < headers.length) {
                                    footerRow.push({
                                        text: ''
                                    });
                                }
                                tableBody.push(footerRow);
                            }

                            // Tambahkan Tabel ke Dokumen
                            doc.content = [{
                                table: {
                                    headerRows: 1,
                                    widths: Array(headers.length).fill(
                                    'auto'), // Perkecil kolom otomatis
                                    body: tableBody,
                                },
                                layout: 'lightHorizontalLines',
                            }, ];

                            // Styling
                            doc.styles.tableHeader = {
                                bold: true,
                                fontSize: 10, // Ukuran lebih kecil
                                color: 'black',
                                fillColor: '#f2f2f2',
                                alignment: 'center',
                            };
                            doc.styles.tableCell = {
                                fontSize: 8, // Ukuran lebih kecil
                            };
                        },
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            stripHtml: false,
                            tfoot: true,
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        },
                        title: function() {
                            var currentDate = new Date();
                            var day = String(currentDate.getDate()).padStart(2,
                            '0'); // Mendapatkan tanggal dengan dua digit
                            var month = String(currentDate.getMonth() + 1).padStart(2,
                            '0'); // Mendapatkan bulan dengan dua digit
                            var year = String(currentDate.getFullYear()).slice(-
                            2); // Mendapatkan dua digit terakhir tahun
                            var formattedDate =
                            `${day}/${month}/${year}`; // Menggabungkan format tanggal/bulan/tahun
                            return 'Laporan Pembayaran Tanggal ' +
                            formattedDate; // Nama file sesuai tanggal
                        },

                        customize: function(win) {
                            $(win.document.body)
                                .find('table')
                                .addClass('compact')
                                .css('font-size', '10px');
                            var tfoot = $('#transaction tfoot').clone();
                            $(win.document.body).find('table').append(tfoot);

                            $(win.document.body)
                                .find('h1') // Selector untuk elemen judul
                                .css({
                                    fontSize: '14px', // Atur ukuran font menjadi 12px
                                    fontWeight: 'bold',
                                    textAlign: 'center',
                                });
                        },
                    },
                ],
            });

            // Tambahkan tombol ekspor ke container
            table
                .buttons()
                .container()
                .appendTo('#excel_wrapper .col-md-6:eq(0)');
        });
    </script>

    <script>
        $(document).ready(function() {
            var table = $('#transaction').DataTable({
                lengthChange: false,
                buttons: [{
                        extend: 'pdf',
                        exportOptions: {
                            stripHtml: false,
                            tfoot: true,
                        },
                        title: function() {
                            var currentDate = new Date();
                            var day = String(currentDate.getDate()).padStart(2,
                            '0'); // Mendapatkan tanggal dengan dua digit
                            var month = String(currentDate.getMonth() + 1).padStart(2,
                            '0'); // Mendapatkan bulan dengan dua digit
                            var year = String(currentDate.getFullYear()).slice(-
                            2); // Mendapatkan dua digit terakhir tahun
                            var formattedDate =
                            `${day}/${month}/${year}`; // Menggabungkan format tanggal/bulan/tahun
                            return 'Laporan Pembayaran Tanggal ' +
                            formattedDate; // Nama file sesuai tanggal
                        },

                        customize: function(doc) {
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
                            $('#transaction thead th').each(function() {
                                headers.push({
                                    text: $(this).text(),
                                    style: 'tableHeader'
                                });
                            });

                            // Isi Tabel
                            var tableBody = [];
                            tableBody.push(headers); // Tambahkan header ke body

                            allData.forEach(function(rowData) {
                                var row = [];
                                rowData.forEach(function(cellData) {
                                    // Hapus tag HTML seperti <li> dan <br>
                                    var cleanedText = cellData
                                        .replace(/<li>/g, '') // Hapus <li>
                                        .replace(/<\/li>/g,
                                        '\n') // Ganti </li> dengan baris baru
                                        .replace(/<br\s*\/?>/g,
                                        '\n') // Hapus <br> dan ganti dengan baris baru
                                        .replace(/<\/?[^>]+(>|$)/g,
                                        ''); // Hapus tag HTML lainnya
                                    row.push({
                                        text: cleanedText.trim(),
                                        style: 'tableCell'
                                    });
                                });
                                tableBody.push(row);
                            });

                            // Footer Tabel (Jika Ada)
                            var tfoot = $('#transaction tfoot').clone();
                            if (tfoot.length) {
                                var footerRow = [];
                                tfoot.find('th').each(function() {
                                    footerRow.push({
                                        text: $(this).text(),
                                        style: 'tableCell'
                                    });
                                });
                                while (footerRow.length < headers.length) {
                                    footerRow.push({
                                        text: ''
                                    });
                                }
                                tableBody.push(footerRow);
                            }

                            // Tambahkan Tabel ke Dokumen
                            doc.content = [{
                                table: {
                                    headerRows: 1,
                                    widths: Array(headers.length).fill(
                                    'auto'), // Perkecil kolom otomatis
                                    body: tableBody,
                                },
                                layout: 'lightHorizontalLines',
                            }, ];

                            // Styling
                            doc.styles.tableHeader = {
                                bold: true,
                                fontSize: 9, // Ukuran lebih kecil
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
                        title: function() {
                            var currentDate = new Date();
                            var day = String(currentDate.getDate()).padStart(2,
                            '0'); // Mendapatkan tanggal dengan dua digit
                            var month = String(currentDate.getMonth() + 1).padStart(2,
                            '0'); // Mendapatkan bulan dengan dua digit
                            var year = String(currentDate.getFullYear()).slice(-
                            2); // Mendapatkan dua digit terakhir tahun
                            var formattedDate =
                            `${day}/${month}/${year}`; // Menggabungkan format tanggal/bulan/tahun
                            return 'Laporan Pembayaran Tanggal ' +
                            formattedDate; // Nama file sesuai tanggal
                        },
                        customize: function(win) {
                            $(win.document.body)
                                .find('table')
                                .addClass('compact')
                                .css('font-size', '10px');
                            var tfoot = $('#transaction tfoot').clone();
                            $(win.document.body).find('table').append(tfoot);

                            $(win.document.body)
                                .find('h1') // Selector untuk elemen judul
                                .css({
                                    fontSize: '14px', // Atur ukuran font menjadi 12px
                                    fontWeight: 'bold',
                                    textAlign: 'center',
                                });
                        },
                    },
                ],
            });

            // Tambahkan tombol ekspor ke container
            table
                .buttons()
                .container()
                .appendTo('#transaction_wrapper .col-md-6:eq(0)');
        });
    </script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 setelah modal dibuka
            $(document).on('shown.bs.modal', function(e) {
                let modal = $(e.target); // Modal yang sedang ditampilkan
                modal.find('#single-select-field').select2({
                    dropdownParent: modal, // Tetapkan parent dropdown ke modal yang aktif
                    placeholder: '-- Nama Bank --',
                    allowClear: true,
                    theme: 'bootstrap-5'
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            function calculateTotal(id) {
                let nominal_in = parseFloat($(`#nominal_in_value_${id}`).val()) || 0;
                let pay_debts = parseFloat($(`#pay_debts_${id}`).val().replace(/[^0-9]/g, '')) || 0;

                let total = nominal_in + pay_debts;
                $(`#nominal_in_${id}`).val('Rp. ' + total.toLocaleString('id-ID'));
            }

            $('[id^=pay_debts_]').on('input', function() {
                let id = $(this).attr('id').split('_')[2];
                calculateTotal(id);
            });

            $('[id^=nominal_in_value_]').each(function() {
                let id = $(this).attr('id').split('_')[2];
                calculateTotal(id);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#bayarbutton').click(function(event) {
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
            $("[id^='lainya_']").on('change', function() {
                var id = $(this).attr('id').split('_')[1]; // Ambil ID dinamis
                toggleValidation(id);
            });

            // Inisialisasi validasi saat halaman dimuat
            $("[id^='lainya_']").each(function() {
                var id = $(this).attr('id').split('_')[1]; // Ambil ID dinamis
                toggleValidation(id);
            });

        }
    </script>
@endpush
