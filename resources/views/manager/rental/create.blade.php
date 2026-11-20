@extends('layouts.master')

@section('title', strtoupper('Rental Alat & Aksesoris'))

@section('content')
    <style>
        .rental-page {
            --rental-primary: #4b266a;
            --rental-border: #e9edf3;
            --rental-muted: #718096;
            color: #263247;
        }

        .rental-page .page-heading {
            margin-bottom: 22px;
        }

        .rental-page .page-heading h4 {
            font-size: 21px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .rental-page .page-heading p {
            color: var(--rental-muted);
            font-size: 13px;
            margin: 0;
        }

        .rental-page .card {
            border: 1px solid var(--rental-border);
            border-radius: 12px;
            box-shadow: 0 3px 14px rgba(20, 35, 60, .045);
            overflow: hidden;
        }

        .rental-page .card-header {
            background: #fff;
            border-bottom: 1px solid var(--rental-border);
            padding: 18px 22px;
        }

        .rental-page .card-header h5 {
            font-size: 15px;
            font-weight: 700;
            margin: 0;
        }

        .rental-page .card-header p {
            color: var(--rental-muted);
            font-size: 12px;
            margin: 5px 0 0;
        }

        .rental-page .card-body {
            padding: 22px;
        }

        .rental-page .section-heading {
            font-size: 13px;
            font-weight: 700;
            color: var(--rental-primary);
            text-transform: uppercase;
            letter-spacing: .5px;
            padding-bottom: 11px;
            margin: 5px 0 17px;
            border-bottom: 1px solid var(--rental-border);
        }

        .rental-page .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #455468;
            margin-bottom: 7px;
        }

        .rental-page .form-control,
        .rental-page .form-select {
            min-height: 40px;
            border-radius: 7px;
            border: 1px solid #dfe4ec;
            font-size: 13px;
            padding: 9px 12px;
            box-shadow: none;
        }

        .rental-page textarea.form-control {
            min-height: 85px;
            resize: vertical;
        }

        .rental-page .form-control:focus,
        .rental-page .form-select:focus {
            border-color: var(--rental-primary);
            box-shadow: 0 0 0 3px rgba(75, 38, 106, .08);
        }

        .rental-page .required {
            color: #e05252;
        }

        .rental-page .form-group {
            margin-bottom: 17px;
        }

        .rental-page .accessory-item {
            display: flex;
            align-items: end;
            gap: 12px;
            padding: 14px;
            margin-bottom: 12px;
            background: #f8f9fc;
            border: 1px solid var(--rental-border);
            border-radius: 9px;
        }

        .rental-page .accessory-select {
            flex: 1;
            min-width: 0;
        }

        .rental-page .accessory-qty {
            width: 100px;
            flex-shrink: 0;
        }

        .rental-page .accessory-delete {
            height: 40px;
            width: 40px;
            flex-shrink: 0;
            border-radius: 7px;
        }

        .rental-page .btn-add-accessory {
            font-size: 12px;
            font-weight: 600;
            border-radius: 7px;
            padding: 9px 14px;
        }

        .rental-page .image-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(105px, 1fr));
            gap: 12px;
            margin-bottom: 15px;
        }

        .rental-page .image-preview-item {
            position: relative;
        }

        .rental-page .image-preview-item img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--rental-border);
        }

        .rental-page .image-preview-item button {
            margin-top: 6px;
            width: 100%;
            font-size: 11px;
        }

        .rental-page .upload-box {
            padding: 22px;
            border: 1px dashed #cbd2df;
            background: #fafbfe;
            border-radius: 9px;
        }

        .rental-page .payment-card {
            position: sticky;
            top: 20px;
        }

        .rental-page .payment-highlight {
            padding: 15px;
            background: #f6f2fa;
            border: 1px solid #e9dff1;
            border-radius: 9px;
            margin-bottom: 20px;
        }

        .rental-page .payment-highlight span {
            display: block;
            color: var(--rental-muted);
            font-size: 11px;
            margin-bottom: 5px;
        }

        .rental-page .payment-highlight strong {
            color: var(--rental-primary);
            font-size: 20px;
        }

        .rental-page .payment-summary {
            padding: 14px;
            background: #f8f9fc;
            border-radius: 9px;
            margin-bottom: 18px;
        }

        .rental-page .payment-summary label {
            font-size: 12px;
            color: var(--rental-muted);
        }

        .rental-page .payment-actions {
            display: flex;
            gap: 10px;
        }

        .rental-page .payment-actions .btn {
            flex: 1;
            min-height: 42px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 7px;
        }

        .rental-page .btn-primary {
            background: var(--rental-primary);
            border-color: var(--rental-primary);
        }

        .rental-page .btn-primary:hover {
            background: #38204f;
            border-color: #38204f;
        }

        .rental-page .select2-container--bootstrap-5 .select2-selection {
            min-height: 40px;
            border-color: #dfe4ec;
            border-radius: 7px;
            font-size: 13px;
        }

        .rental-page .invalid-feedback {
            font-size: 11px;
        }

        @media(max-width: 991px) {
            .rental-page .payment-card {
                position: static;
            }
        }

        @media(max-width: 575px) {
            .rental-page .card-body {
                padding: 16px;
            }

            .rental-page .accessory-item {
                flex-wrap: wrap;
            }

            .rental-page .accessory-select {
                flex-basis: 100%;
            }

            .rental-page .accessory-qty {
                flex: 1;
                width: auto;
            }
        }
    </style>

    <div class="rental-page">

        <div class="page-heading">
            <h4>
                <i class="bx bx-package me-1"></i>
                {{ isset($rentals) ? 'Edit Rental' : 'Tambah Rental Baru' }}
            </h4>
            <p>Kelola informasi pelanggan, alat, aksesoris, dan pembayaran rental.</p>
        </div>

        <form action="{{ $url }}" method="POST"
              enctype="multipart/form-data" id="myForm">
            @csrf

            @isset($rentals)
                @method('PUT')
            @endisset

            <div class="row g-3">

                {{-- DATA RENTAL --}}
                <div class="col-lg-8">

                    <div class="card mb-3">

                        <div class="card-header">
                            <h5><i class="bx bx-file me-1"></i> Informasi Rental</h5>
                            <p>Informasi dasar transaksi rental.</p>
                        </div>

                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6 form-group">
                                    <label class="form-label">Nomor Invoice</label>
                                    <input type="text" name="no_inv"
                                           class="form-control @error('no_inv') is-invalid @enderror"
                                           value="{{ old('no_inv', $rentals->no_inv ?? '') }}"
                                           placeholder="Isi jika menggunakan invoice custom">

                                    @error('no_inv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 form-group">
                                    <label class="form-label">
                                        <span class="required">*</span> Tanggal Invoice
                                    </label>
                                    <input type="text" name="tgl_inv"
                                           class="form-control datepicker @error('tgl_inv') is-invalid @enderror"
                                           value="{{ old('tgl_inv', $rentals->tgl_inv ?? '') }}"
                                           placeholder="Tanggal invoice">

                                    @error('tgl_inv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 form-group">
                                    <label class="form-label">Tanggal Pembayaran</label>
                                    <input type="text" name="date_pay"
                                           class="form-control datepicker"
                                           value="{{ old('date_pay', isset($rentals) && $rentals->debt->isNotEmpty() ? $rentals->debt->first()->date_pay : '') }}"
                                           placeholder="Tanggal bayar">
                                </div>

                                <div class="col-12 section-heading">
                                    <i class="bx bx-user me-1"></i>
                                    Informasi Pelanggan
                                </div>

                                @if(isset($rentals))
                                    <div class="col-12 form-group">
                                        <label class="form-label">
                                            <span class="required">*</span> Nama Pelanggan
                                        </label>

                                        <select name="customer_id"
                                                id="single-select-field"
                                                class="form-control">
                                            <option value="">-- Pilih Pelanggan --</option>

                                            @foreach($cust as $id => $name)
                                                @php
                                                    $isProblem = in_array($id, $problemCustomers ?? []);
                                                @endphp

                                                <option value="{{ $id }}"
                                                    {{ old('customer_id', $rentals->customer_id) == $id ? 'selected' : '' }}>
                                                    {{ $name }} {{ $isProblem ? '(Problem)' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <div class="col-md-9 form-group">
                                        <label class="form-label">
                                            <span class="required">*</span> Nama Pelanggan
                                        </label>

                                        <select name="customer_id"
                                                id="single-select-field"
                                                class="form-control @error('customer_id') is-invalid @enderror">
                                            <option value="">-- Pilih Pelanggan --</option>

                                            @foreach($cust as $id => $name)
                                                @php
                                                    $isProblem = in_array($id, $problemCustomers ?? []);
                                                @endphp

                                                <option value="{{ $id }}"
                                                    {{ old('customer_id') == $id ? 'selected' : '' }}>
                                                    {{ $name }} {{ $isProblem ? '(Problem)' : '' }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('customer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 form-group d-flex align-items-end">
                                        <a href="{{ route('manager.customer.create') }}"
                                           class="btn btn-outline-primary w-100"
                                           style="height:40px;font-size:12px">
                                            <i class="bx bx-user-plus"></i> Tambah Pelanggan
                                        </a>
                                    </div>
                                @endif

                                <div class="col-12 section-heading mt-3">
                                    <i class="bx bx-package me-1"></i>
                                    Informasi Alat
                                </div>

                                <div class="col-12 form-group">
                                    <label class="form-label">
                                        <span class="required">*</span> Pilih Alat
                                    </label>

                                    <select name="item_id[]" id="multiple-select-field"
                                            class="form-control @error('item_id') is-invalid @enderror"
                                            multiple data-placeholder="Pilih alat rental">

                                        @foreach($item as $items)
                                            <option value="{{ $items->id }}"
                                                    @if(
                                                        (isset($rentals) && in_array($items->id, json_decode($rentals->item_id, true) ?? [])) ||
                                                        (is_array(old('item_id')) && in_array($items->id, old('item_id')))
                                                    ) selected @endif>
                                                {{ $items->name }} ({{ $items->no_seri }})
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('item_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 form-group">
                                    <label class="form-label">Keterangan Alat</label>
                                    <textarea class="form-control"
                                              name="keterangan_item"
                                              placeholder="Tambahkan keterangan alat...">{{ old('keterangan_item', $rentals->keterangan_item ?? '') }}</textarea>
                                </div>

                                <div class="col-12 section-heading mt-3">
                                    <i class="bx bx-wrench me-1"></i>
                                    Aksesoris Rental
                                </div>

                                <div class="col-12">

                                    <div id="dynamic-fields">

                                        @php
                                            // Nilai old() diprioritaskan agar input tetap terisi
                                            // apabila form dirender ulang oleh validasi Laravel.
                                            $oldAccess = old('access');
                                            $oldAccessQty = old('accessories_quantity');
                                            $hasOldAccessoryInput = is_array($oldAccess) || is_array($oldAccessQty);

                                            if ($hasOldAccessoryInput) {
                                                $accessoryRows = [];
                                                $oldAccess = is_array($oldAccess) ? $oldAccess : [];
                                                $oldAccessQty = is_array($oldAccessQty) ? $oldAccessQty : [];
                                                $rowCount = max(count($oldAccess), count($oldAccessQty));
                                                for ($i = 0; $i < $rowCount; $i++) {
                                                    $accessoryRows[] = [
                                                        'accessories_id' => $oldAccess[$i] ?? '',
                                                        'accessories_quantity' => $oldAccessQty[$i] ?? '',
                                                    ];
                                                }
                                            } elseif (isset($rentals) && $rentals->accessoriescategory->isNotEmpty()) {
                                                $accessoryRows = $rentals->accessoriescategory->map(function ($row) {
                                                    return [
                                                        'accessories_id' => $row->accessories_id,
                                                        'accessories_quantity' => $row->accessories_quantity,
                                                    ];
                                                })->toArray();
                                            } else {
                                                $accessoryRows = [[
                                                    'accessories_id' => '',
                                                    'accessories_quantity' => '',
                                                ]];
                                            }
                                        @endphp

                                        @foreach($accessoryRows as $accessoryRow)
                                            <div class="accessory-item">
                                                <div class="accessory-select">
                                                    <label class="form-label">Aksesoris</label>
                                                    <select name="access[]" class="form-control accessory-select2">
                                                        <option value="">-- Pilih Aksesoris --</option>
                                                        @foreach($acces as $accessory)
                                                            <option value="{{ $accessory->id }}"
                                                                {{ (string) $accessory->id === (string) $accessoryRow['accessories_id'] ? 'selected' : '' }}>
                                                                {{ $accessory->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="accessory-qty">
                                                    <label class="form-label">Qty</label>
                                                    <input type="number"
                                                           class="form-control"
                                                           name="accessories_quantity[]"
                                                           min="1"
                                                           value="{{ $accessoryRow['accessories_quantity'] }}"
                                                           required>
                                                </div>

                                                <button type="button"
                                                        class="btn btn-outline-danger accessory-delete remove-field"
                                                        title="Hapus aksesoris">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        @endforeach

                                    </div>

                                    <button type="button" id="add-field"
                                            class="btn btn-outline-primary btn-add-accessory mt-2">
                                        <i class="bx bx-plus-circle me-1"></i>
                                        Tambah Aksesoris
                                    </button>

                                </div>

                                <div class="col-12 form-group mt-3">
                                    <label class="form-label">Keterangan Aksesoris</label>
                                    <textarea class="form-control"
                                              name="keterangan_acces"
                                              placeholder="Keterangan tambahan aksesoris...">{{ old('keterangan_acces', $rentals->keteranganacces ?? '') }}</textarea>
                                </div>

                                <div class="col-12 section-heading mt-3">
                                    <i class="bx bx-buildings me-1"></i>
                                    Informasi Perusahaan
                                </div>

                                <div class="col-md-6 form-group">
                                    <label class="form-label">Nama Perusahaan</label>
                                    <input type="text" name="name_company"
                                           class="form-control"
                                           value="{{ old('name_company', $rentals->name_company ?? '') }}"
                                           placeholder="Nama perusahaan">
                                </div>

                                <div class="col-md-6 form-group">
                                    <label class="form-label">Nomor Perusahaan</label>
                                    <input type="number" name="phone_company"
                                           class="form-control"
                                           value="{{ old('phone_company', $rentals->phone_company ?? '') }}"
                                           placeholder="Nomor telepon">
                                </div>

                                <div class="col-12 form-group">
                                    <label class="form-label">Nomor PO</label>
                                    <input type="text" name="no_po"
                                           class="form-control"
                                           value="{{ old('no_po', $rentals->no_po ?? '') }}"
                                           placeholder="Nomor purchase order">
                                </div>

                                <div class="col-12 form-group">
                                    <label class="form-label">Alamat Perusahaan</label>
                                    <textarea name="addres_company"
                                              class="form-control"
                                              placeholder="Alamat lengkap perusahaan">{{ old('addres_company', $rentals->addres_company ?? '') }}</textarea>
                                </div>

                                <div class="col-12 section-heading mt-3">
                                    <i class="bx bx-calendar me-1"></i>
                                    Periode Rental
                                </div>

                                <div class="col-md-6 form-group">
                                    <label class="form-label">
                                        <span class="required">*</span> Tanggal Mulai
                                    </label>
                                    <input type="text" name="date_start"
                                           class="form-control datepicker @error('date_start') is-invalid @enderror"
                                           value="{{ old('date_start', $rentals->date_start ?? '') }}"
                                           placeholder="Tanggal mulai">

                                    @error('date_start')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 form-group">
                                    <label class="form-label">
                                        <span class="required">*</span> Tanggal Berakhir
                                    </label>
                                    <input type="text" name="date_end"
                                           class="form-control datepicker @error('date_end') is-invalid @enderror"
                                           value="{{ old('date_end', $rentals->date_end ?? '') }}"
                                           placeholder="Tanggal berakhir">

                                    @error('date_end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 section-heading mt-3">
                                    <i class="bx bx-image me-1"></i>
                                    Dokumentasi Rental
                                </div>

                                <div class="col-12 form-group">

                                    @if(isset($rentals) && $rentals->image)

                                        <label class="form-label">Gambar Saat Ini</label>

                                        <div class="image-preview">

                                            @foreach(json_decode($rentals->image, true) ?? [] as $image)
                                                <div class="image-preview-item">
                                                    <img src="{{ asset('images/rental/' . $image) }}"
                                                         alt="Gambar rental">

                                                    <button type="button"
                                                            class="btn btn-outline-danger btn-sm delete-image"
                                                            data-image="{{ $image }}">
                                                        <i class="bx bx-trash"></i> Hapus
                                                    </button>
                                                </div>
                                            @endforeach

                                        </div>
                                    @endif

                                    <div class="upload-box">
                                        <label class="form-label">
                                            Upload Gambar Rental
                                        </label>

                                        <input type="file"
                                               name="image[]"
                                               class="form-control"
                                               accept="image/*"
                                               id="image-uploadify"
                                               multiple>

                                        <small class="text-muted d-block mt-2">
                                            Pilih satu atau beberapa gambar dokumentasi rental.
                                        </small>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                {{-- PEMBAYARAN --}}
                <div class="col-lg-4">

                    <div class="card payment-card">

                        <div class="card-header">
                            <h5>
                                <i class="bx bx-wallet me-1"></i>
                                Informasi Pembayaran
                            </h5>
                            <p>Kelola rincian pembayaran transaksi rental.</p>
                        </div>

                        <div class="card-body">

                            <div class="payment-highlight">
                                <span>Total Invoice</span>
                                <strong>Rp <span id="invoice-preview">0</span></strong>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <span class="required">*</span> Total Invoice
                                </label>

                                <input type="text" name="total_invoice"
                                       id="total_invoice"
                                       class="form-control currency-input @error('total_invoice') is-invalid @enderror"
                                       value="{{ old('total_invoice', $rentals->total_invoice ?? 0) }}"
                                       placeholder="0">

                                @error('total_invoice')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">

                            </div>

                            <div class="form-group">

                            </div>

                            <div class="payment-summary">
                                <div class="row g-3">

                                    <div class="col-6">
                                        <label class="form-label">
                                            <span class="required">*</span> Pembayaran
                                        </label>
                                        <input type="text" name="nominal_in"
                                               id="nominal_in"
                                               class="form-control currency-input @error('nominal_in') is-invalid @enderror"
                                               value="{{ old('nominal_in', $rentals->nominal_in ?? 0) }}"
                                               placeholder="0">
                                        @error('nominal_in')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Sisa Pembayaran</label>
                                        <input type="number" name="nominal_out"
                                               id="nominal_out"
                                               class="form-control"
                                               value="{{ old('nominal_out', $rentals->nominal_out ?? 0) }}"
                                               placeholder="0">
                                    </div>
                                    <div class="col-6">
                                        <label>Diskon</label>
                                        <input type="number" name="diskon"
                                               class="form-control mt-2"
                                               value="{{ old('diskon', $rentals->diskon ?? 0) }}">
                                    </div>

                                    <div class="col-6">
                                        <label>PPN</label>
                                        <input type="number" name="ppn"
                                               class="form-control mt-2"
                                               value="{{ old('ppn', $rentals->ppn ?? 0) }}">
                                    </div>

                                    <div class="col-6">
                                        <label>Biaya Ganti</label>
                                        <input type="number" name="biaya_ganti"
                                               class="form-control mt-2"
                                               value="{{ old('fee', $rentals->biaya_ganti ?? 0) }}">
                                    </div>

                                    <div class="col-6">
                                        <label>Fee</label>
                                        <input type="number" name="fee"
                                               class="form-control mt-2"
                                               value="{{ old('fee', $rentals->fee ?? 0) }}">
                                    </div>

                                </div>
                            </div>

                            <div class="section-heading">
                                Informasi Transfer
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label">Bank</label>

                                    {{ html()->select(
                                        'bank_id',
                                        $bank,
                                        old('bank_id', isset($rentals) && $rentals->debt->isNotEmpty()
                                            ? $rentals->debt->first()->bank_id
                                            : null)
                                    )
                                        ->class(['form-control', 'is-invalid' => $errors->has('bank_id')])
                                        ->id('bank-select')
                                        ->placeholder('-- Pilih Bank --')
                                    }}
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Penerima</label>
                                    <input type="text" name="penerima"
                                           class="form-control"
                                           value="{{ old('penerima', isset($rentals) && $rentals->debt->isNotEmpty()
                                        ? $rentals->debt->first()->penerima
                                        : '') }}"
                                           placeholder="Nama penerima pembayaran">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Keterangan Pembayaran</label>

                                <textarea name="description"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Keterangan pembayaran...">{{ old('description', isset($rentals) && $rentals->debt->isNotEmpty()
                                    ? $rentals->debt->first()->description
                                    : '') }}</textarea>

                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="card-footer bg-white p-3">
                            <div class="payment-actions">

                                <button type="submit"
                                        class="btn btn-primary"
                                        id="submitBtn">
                                    <i class="bx bx-save me-1"></i>
                                    Simpan Rental
                                </button>

                                <a href="{{ route('manager.rental.index') }}"
                                   class="btn btn-light border">
                                    Batal
                                </a>

                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </form>

    </div>
@endsection
@push('head')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet"/>
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ URL::to('assets/plugins/validation/jquery.validate.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            const $form = $('#myForm');
            const $submitButton = $('#submitBtn');

            // ---------------------------------------------------------
            // INTERCEPT SUBMIT SECARA NATIVE (CAPTURE PHASE)
            // ---------------------------------------------------------
            // Handler ini sengaja dipasang pada capture phase agar submit HTML
            // biasa tidak pernah berjalan, termasuk ketika jQuery Validate gagal
            // dimuat atau ada konflik handler submit dari script lain.
            const rentalForm = document.getElementById('myForm');
            let rentalSubmitting = false;

            if (rentalForm) {
                rentalForm.addEventListener('submit', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    event.stopImmediatePropagation();

                    if (rentalSubmitting) return;

                    // Validasi client-side jika plugin tersedia. Jika plugin tidak
                    // tersedia, validasi tetap dilakukan oleh Laravel melalui AJAX.
                    const validator = $form.data('validator');
                    if (validator && !validator.form()) {
                        return;
                    }

                    clearAjaxErrors();
                    rentalSubmitting = true;

                    const originalButtonHtml = $submitButton.html();
                    const $currencyFields = $form.find('.currency-input');

                    // Simpan nilai tampilan agar format Rupiah dapat dikembalikan
                    // jika server menolak penyimpanan.
                    const currencyDisplayValues = [];
                    $currencyFields.each(function (index) {
                        currencyDisplayValues[index] = $(this).val();
                        $(this).val(numericValue($(this).val()));
                    });

                    $submitButton.prop('disabled', true)
                        .html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

                    $.ajax({
                        url: $form.attr('action'),
                        method: ($form.attr('method') || 'POST').toUpperCase(),
                        data: new FormData(rentalForm),
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function (response) {
                            if (response && response.success === false) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal menyimpan',
                                    text: response.message || 'Data rental gagal disimpan.'
                                });
                                return;
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message || 'Data rental berhasil disimpan.',
                                confirmButtonText: 'OK',
                                allowOutsideClick: false
                            }).then(function () {
                                if (response && response.redirect) {
                                    window.location.href = response.redirect;
                                } else {
                                    window.location.href = @json(route('manager.rental.index'));
                                }
                            });
                        },
                        error: function (xhr) {
                            // Kembalikan format Rupiah tanpa mengubah nilai input lain.
                            $currencyFields.each(function (index) {
                                $(this).val(currencyDisplayValues[index] ?? formatRupiah($(this).val()));
                            });

                            const response = xhr.responseJSON || {};
                            if (xhr.status === 422 && response.errors) {
                                showValidationErrors(response.errors);
                                return;
                            }

                            let message = response.message ||
                                'Terjadi kesalahan saat menyimpan. Periksa kembali data dan koneksi Anda.';

                            if (xhr.status === 419) {
                                message = 'Sesi telah berakhir (419). Silakan muat ulang halaman untuk memperbarui sesi, lalu isi kembali form.';
                            } else if (xhr.status === 0) {
                                message = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
                            } else if (xhr.status === 200 && xhr.responseText && xhr.responseText.indexOf('<html') !== -1) {
                                message = 'Server mengembalikan halaman HTML, bukan JSON. Controller masih melakukan redirect saat gagal. Ubah respons validasi/error controller menjadi JSON HTTP 422.';
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal menyimpan',
                                text: message,
                                confirmButtonText: 'Tutup'
                            });
                        },
                        complete: function () {
                            rentalSubmitting = false;
                            $submitButton.prop('disabled', false).html(originalButtonHtml);
                        }
                    });
                }, true);
            }



            // ---------------------------------------------------------
            // Flatpickr
            // ---------------------------------------------------------
            $('.datepicker').flatpickr({
                dateFormat: 'Y-m-d',
                allowInput: true
            });

            // ---------------------------------------------------------
            // Select2
            // ---------------------------------------------------------
            $('#single-select-field, #bank-select').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            $('#multiple-select-field').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih alat rental'
            });

            function initAccessorySelect(element) {
                $(element).select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Pilih aksesoris'
                });
            }

            $('.accessory-select2').each(function () {
                initAccessorySelect(this);
            });

            // ---------------------------------------------------------
            // Tambah aksesoris secara dinamis
            // ---------------------------------------------------------
            $('#add-field').on('click', function () {
                let accessoryOptions = `
                    <option value="">-- Pilih Aksesoris --</option>
                    @foreach($acces as $accessory)
                <option value="{{ $accessory->id }}">
                            {{ addslashes($accessory->name) }}
                </option>
@endforeach
                `;

                let html = `
                    <div class="accessory-item">
                        <div class="accessory-select">
                            <label class="form-label">Aksesoris</label>
                            <select name="access[]" class="form-control accessory-select2">
                                ${accessoryOptions}
                            </select>
                        </div>

                        <div class="accessory-qty">
                            <label class="form-label">Qty</label>
                            <input type="number"
                                   class="form-control"
                                   name="accessories_quantity[]"
                                   min="1">
                        </div>

                        <button type="button"
                                class="btn btn-outline-danger accessory-delete remove-field"
                                title="Hapus aksesoris">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                `;

                const $newItem = $(html);
                $('#dynamic-fields').append($newItem);
                initAccessorySelect($newItem.find('.accessory-select2'));

                // Jangan reset validator karena dapat menghapus pesan error
                // dari field lain yang sudah diisi pengguna.
            });

            // ---------------------------------------------------------
            // Hapus baris aksesoris
            // ---------------------------------------------------------
            $(document).on('click', '.remove-field', function () {
                const $item = $(this).closest('.accessory-item');
                $item.find('.accessory-select2').each(function () {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                });
                $item.remove();
                // Pesan validasi field lain tetap dipertahankan.
            });

            // ---------------------------------------------------------
            // Format rupiah
            // ---------------------------------------------------------
            function formatRupiah(value) {
                const number = String(value ?? '').replace(/\D/g, '');
                return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function numericValue(value) {
                return Number(String(value ?? '').replace(/\D/g, '')) || 0;
            }

            $('.currency-input').each(function () {
                $(this).val(formatRupiah($(this).val()));
            });

            function updatePayment() {
                const invoice = numericValue($('#total_invoice').val());
                const payment = numericValue($('#nominal_in').val());

                $('#invoice-preview').text(formatRupiah(invoice));
                $('#nominal_out').val(invoice - payment);
            }

            $('.currency-input').on('input', function () {
                const oldValue = this.value;
                const cursorPosition = this.selectionStart;
                const formatted = formatRupiah(oldValue);

                $(this).val(formatted);
                updatePayment();

                // Pertahankan posisi kursor sebisa mungkin setelah format.
                if (document.activeElement === this) {
                    const difference = formatted.length - oldValue.length;
                    const nextPosition = Math.max(0, cursorPosition + difference);
                    this.setSelectionRange(nextPosition, nextPosition);
                }
            });

            updatePayment();

            // ---------------------------------------------------------
            // Helpers tampilan error AJAX
            // ---------------------------------------------------------
            function clearAjaxErrors() {
                $form.find('.ajax-validation-error').remove();
                $form.find('.is-invalid').removeClass('is-invalid');
                $form.find('.select2-selection').removeClass('is-invalid');
                $('#ajax-validation-summary').remove();
            }

            function fieldNameFromLaravelKey(key) {
                // Contoh: access.0 menjadi access[0]
                return key.replace(/\.(\d+)/g, '[$1]');
            }

            function showValidationErrors(errors) {
                clearAjaxErrors();

                const messages = [];

                $.each(errors, function (key, fieldMessages) {
                    const message = Array.isArray(fieldMessages)
                        ? fieldMessages[0]
                        : fieldMessages;

                    messages.push(message);

                    const fieldName = fieldNameFromLaravelKey(key);
                    let $field = $form.find('[name="' + fieldName + '"]').first();

                    // Fallback untuk nama array seperti access.0 / access[0].
                    if (!$field.length && key.indexOf('.') !== -1) {
                        const parts = key.split('.');
                        const baseName = parts[0] + '[]';
                        const index = parseInt(parts[1], 10);
                        $field = $form.find('[name="' + baseName + '"]').eq(index);
                    }

                    if (!$field.length) {
                        $field = $form.find('[name="' + key + '"]').first();
                    }

                    if ($field.length) {
                        $field.addClass('is-invalid');

                        if ($field.hasClass('select2-hidden-accessible')) {
                            const $container = $field.next('.select2-container');
                            $container.find('.select2-selection').addClass('is-invalid');

                            $('<div>', {
                                class: 'invalid-feedback d-block ajax-validation-error',
                                text: message
                            }).insertAfter($container);
                        } else {
                            $('<div>', {
                                class: 'invalid-feedback d-block ajax-validation-error',
                                text: message
                            }).insertAfter($field);
                        }
                    }
                });

                if (messages.length) {
                    const list = $('<ul class="mb-0 ps-3"></ul>');
                    messages.forEach(function (message) {
                        $('<li></li>').text(message).appendTo(list);
                    });

                    $('<div>', {
                        id: 'ajax-validation-summary',
                        class: 'alert alert-danger mb-3'
                    }).append('<strong>Periksa kembali data berikut:</strong>').append(list)
                        .prependTo($form);
                }

                // Tidak melakukan reload; pengguna tetap berada di form.
                if (messages.length) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validasi belum terpenuhi',
                        text: 'Periksa pesan kesalahan pada form, lalu perbaiki datanya.',
                        confirmButtonText: 'Mengerti'
                    });
                }
            }

            // ---------------------------------------------------------
            // jQuery Validate
            // ---------------------------------------------------------
            if ($form.data('validator')) {
                $form.removeData('validator');
                $form.removeData('unobtrusiveValidation');
            }

            $form.validate({
                // Select2 menyembunyikan select asli. Tetap validasi select2.
                ignore: ':hidden:not(.select2-hidden-accessible)',
                errorElement: 'div',
                errorClass: 'invalid-feedback d-block',

                rules: {
                    tgl_inv: {
                        required: true
                    },
                    customer_id: {
                        required: true
                    },
                    'item_id[]': {
                        required: true,
                        minlength: 1
                    },
                    date_start: {
                        required: true
                    },
                    date_end: {
                        required: true
                    },
                    total_invoice: {
                        required: true
                    },
                    nominal_in: {
                        required: true
                    }
                },

                messages: {
                    tgl_inv: {
                        required: 'Tanggal invoice wajib diisi.'
                    },
                    customer_id: {
                        required: 'Pelanggan wajib dipilih.'
                    },
                    'item_id[]': {
                        required: 'Pilih minimal satu alat rental.',
                        minlength: 'Pilih minimal satu alat rental.'
                    },
                    date_start: {
                        required: 'Tanggal mulai rental wajib diisi.'
                    },
                    date_end: {
                        required: 'Tanggal berakhir rental wajib diisi.'
                    },
                    total_invoice: {
                        required: 'Total invoice wajib diisi.'
                    },
                    nominal_in: {
                        required: 'Nominal pembayaran wajib diisi.'
                    }
                },

                errorPlacement: function (error, element) {
                    if (element.hasClass('select2-hidden-accessible')) {
                        error.insertAfter(element.next('.select2-container'));
                    } else {
                        error.insertAfter(element);
                    }
                },

                highlight: function (element) {
                    $(element).addClass('is-invalid');

                    if ($(element).hasClass('select2-hidden-accessible')) {
                        $(element).next('.select2-container')
                            .find('.select2-selection')
                            .addClass('is-invalid');
                    }
                },

                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');

                    if ($(element).hasClass('select2-hidden-accessible')) {
                        $(element).next('.select2-container')
                            .find('.select2-selection')
                            .removeClass('is-invalid');
                    }
                },

                onkeyup: function (element) {
                    $(element).valid();
                },

                onchange: function (element) {
                    $(element).valid();
                },

            });



            // ---------------------------------------------------------
            // Hapus gambar lama melalui AJAX
            // ---------------------------------------------------------
            $(document).on('click', '.delete-image', function () {
                const image = $(this).data('image');
                const $row = $(this).closest('.image-preview-item');

                Swal.fire({
                    title: 'Hapus gambar?',
                    text: 'Gambar yang dihapus tidak dapat dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc3545'
                }).then(function (result) {
                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: '{{ route('manager.rental.deleteImage') }}',
                        method: 'POST',
                        dataType: 'json',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        data: {
                            _token: '{{ csrf_token() }}',
                            image: image
                        },

                        success: function (response) {
                            if (response.success) {
                                $row.remove();

                                Swal.fire(
                                    'Berhasil!',
                                    response.message || 'Gambar berhasil dihapus.',
                                    'success'
                                );
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    response.message || 'Gambar gagal dihapus.',
                                    'error'
                                );
                            }
                        },

                        error: function (xhr) {
                            const message = xhr.responseJSON?.message ||
                                'Terjadi kesalahan saat menghapus gambar.';

                            Swal.fire('Error!', message, 'error');
                        }
                    });
                });
            });
        });
    </script>
@endpush
