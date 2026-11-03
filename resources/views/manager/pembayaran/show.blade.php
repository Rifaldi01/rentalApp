@extends('layouts.master')
@section('title', 'Detail Pembayaran')
@section('content')
    <style>
        .payment-detail .card {
            border: 1px solid #e7ebf0;
            border-radius: 12px;
            box-shadow: 0 3px 14px rgba(20, 35, 60, .045);
        }

        .payment-hero {
            background: linear-gradient(120deg, #123d73, #1677d2);
            color: #fff;
            border-radius: 12px;
            padding: 22px 24px;
        }

        .payment-hero .label {
            color: rgba(255, 255, 255, .72);
            font-size: 12px;
        }

        .payment-hero .value {
            font-size: 25px;
            font-weight: 800;
            letter-spacing: .2px;
        }

        .payment-section-title {
            color: #344054;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .payment-info-label {
            color: #8a94a3;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .payment-info-value {
            color: #273449;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .payment-detail .table thead th {
            background: #f5f7fa;
            color: #475467;
            font-size: 12px;
            white-space: nowrap;
        }

        .payment-detail .table td {
            vertical-align: middle;
            font-size: 13px;
        }

        .installment-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 30px;
            border-radius: 8px;
            color: #1458a6;
            background: #eaf3ff;
            font-weight: 700;
        }

        .payment-modal-header {
            background: linear-gradient(120deg, #123d73, #1677d2);
            color: #fff;
        }

        .payment-modal-header .btn-close {
            filter: invert(1);
        }

        .payment-input-label {
            font-size: 13px;
            font-weight: 600;
            color: #475467;
            margin-bottom: 6px;
        }

        .payment-detail .form-control, .payment-detail .form-select {
            min-height: 42px;
        }
    </style>

    <div class="payment-detail">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h4 class="mb-1 fw-bold text-dark">Detail Pembayaran</h4>
                <div class="text-muted small">Rincian tagihan rental dan histori pembayaran cicilan.</div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('manager.pembayaran.invoice', $rentals->id) }}"
                   target="_blank"
                   class="btn btn-primary">
                    <i class="bx bx-printer me-1"></i> Cetak Invoice
                </a>

                <a href="{{ route('manager.pembayaran.index') }}"
                   class="btn btn-outline-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Kembali ke Data Cicilan
                </a>
            </div>

        </div>

        <div class="payment-hero mb-3">
            <div class="row align-items-center g-3">
                <div class="col-md-4">
                    <div class="label">Invoice Rental</div>
                    <div class="h5 fw-bold mb-1 text-white">{{ $rentals->no_inv ?: '-' }}</div>
                    <div class="label">{{ $rentals->cust->name ?? 'Customer dihapus' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="label">Total Sudah Dibayar</div>
                    <div class="value">Rp {{ number_format($rentals->nominal_in,0,',','.') }}</div>
                </div>
                <div class="col-md-3">
                    <div class="label">Sisa Tagihan</div>
                    <div class="value">Rp {{ number_format($rentals->nominal_out,0,',','.') }}</div>
                </div>
                <div class="col-md-2 text-md-end">
                    @if($isPaid)
                        <span class="badge bg-success px-3 py-2 fs-6"><i
                                class="bx bx-check-circle me-1"></i> LUNAS</span>
                    @else
                        <span class="badge bg-warning text-dark px-3 py-2 fs-6">BELUM LUNAS</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="payment-section-title"><i class="bx bx-receipt me-1 text-primary"></i> Informasi
                            Transaksi
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="payment-info-label">Nama Customer</div>
                                <div class="payment-info-value">{{ $rentals->cust->name ?? '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="payment-info-label">Tanggal Invoice</div>
                                <div
                                    class="payment-info-value">{{ $rentals->tgl_inv ? formatId($rentals->tgl_inv) : '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="payment-info-label">Nomor PO</div>
                                <div class="payment-info-value">{{ $rentals->no_po ?: '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="payment-info-label">Tanggal Mulai Rental</div>
                                <div
                                    class="payment-info-value">{{ $rentals->date_start ? formatId($rentals->date_start) : '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="payment-info-label">Tanggal Selesai Rental</div>
                                <div
                                    class="payment-info-value">{{ $rentals->date_end ? formatId($rentals->date_end) : '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="payment-info-label">Kontak Customer</div>
                                <div class="payment-info-value">{{ $rentals->cust->phone ?? '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="payment-info-label">Total Invoice</div>
                                <div class="payment-info-value">
                                    Rp {{ number_format((int)$rentals->total_invoice,0,',','.') }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="payment-info-label">Diskon</div>
                                <div class="payment-info-value">
                                    Rp {{ number_format((int)$rentals->diskon,0,',','.') }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="payment-info-label">Fee</div>
                                <div class="payment-info-value">
                                    Rp {{ number_format((int)$rentals->fee,0,',','.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="payment-section-title"><i class="bx bx-wallet me-1 text-primary"></i> Ringkasan
                            Pembayaran
                        </div>
                        <div class="d-flex justify-content-between mb-3"><span class="text-muted small">Jumlah pembayaran</span><strong>{{ $payments->count() }}
                                kali</strong></div>
                        <div class="d-flex justify-content-between mb-3"><span
                                class="text-muted small">Total diterima</span><strong
                                class="text-success">Rp {{ number_format($rentals->nominal_in,0,',','.') }}</strong></div>
                        <div class="d-flex justify-content-between mb-3"><span
                                class="text-muted small">Sisa piutang</span><strong
                                class="{{ $isPaid ? 'text-success' : 'text-danger' }}">Rp {{ number_format($rentals->nominal_out,0,',','.') }}</strong>
                        </div>
                        <div class="mt-auto pt-2">
                            @if($isPaid)
                                <span class="badge bg-success w-100 py-3 fs-6"><i class="bx bx-check-circle me-1"></i> LUNAS — Tidak ada tagihan</span>
                            @else
                                <button type="button" class="btn btn-primary btn-lg w-100" data-bs-toggle="modal"
                                        data-bs-target="#paymentModal">
                                    <i class="bx bx-credit-card me-1"></i> Bayar Sekarang
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="payment-section-title"><i class="bx bx-package me-1 text-primary"></i> Item yang Disewa
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                        <tr>
                            <th style="width:55px">No</th>
                            <th>Nama Item</th>
                            <th>Nomor Seri</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($items as $index=>$item)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td class="fw-semibold">{{ $item->name ?? '-' }}</td>
                                <td>{{ $item->no_seri ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Data item tidak tersedia.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($rentals->keterangan_item)
                    <div class="small text-muted mt-2">Catatan item: {{ $rentals->keterangan_item }}</div>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="payment-section-title"><i class="bx bx-cube me-1 text-primary"></i> Accessories yang Disewa
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                        <tr>
                            <th style="width:55px">No</th>
                            <th>Nama Accessories</th>
                            <th>Kode</th>
                            <th class="text-end">Jumlah</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($accessories as $index=>$row)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td class="fw-semibold">{{ $row->accessory->name ?? 'Accessories dihapus' }}</td>
                                <td>{{ $row->accessory->code_acces ?? '-' }}</td>
                                <td class="text-end">{{ $row->accessories_quantity ?? 0 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Tidak ada accessories pada transaksi
                                    ini.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($rentals->keterangan_acces)
                    <div class="small text-muted mt-2">Catatan accessories: {{ $rentals->keterangan_acces }}</div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div class="payment-section-title mb-0"><i class="bx bx-history me-1 text-primary"></i> Riwayat
                        Pembayaran / Cicilan
                    </div>
                    <span class="badge bg-light text-dark border">{{ $payments->count() }} catatan pembayaran</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mb-0">
                        <thead>
                        <tr>
                            <th class="text-center">Cicilan</th>
                            <th>Tanggal Bayar</th>
                            <th>Nominal</th>
                            <th>Bank / Metode</th>
                            <th>Penerima</th>
                            <th>Keterangan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td class="text-center">
            <span class="installment-number">
                #{{ $payment->installment_no ?: $loop->iteration }}
            </span>
                                </td>

                                <td>
                                    {{ $payment->date_pay ? formatId($payment->date_pay) : '-' }}
                                </td>

                                <td class="fw-bold text-success text-nowrap">
                                    Rp {{ number_format((int) $payment->pay_debts, 0, ',', '.') }}
                                </td>

                                <td>
                                    {{ $payment->bank->name ?? ($payment->description ? 'Lainnya' : '-') }}
                                </td>

                                <td>{{ $payment->penerima ?: '-' }}</td>

                                <td>{{ $payment->description ?: '-' }}</td>

                                <td class="text-center">
                                    <form
                                        action="{{ route($routePrefix . '.pembayaran.cicilan.destroy', [
                    $rentals->id,
                    $payment->id
                ]) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus cicilan ini? Nominalnya akan dikembalikan ke sisa tagihan.')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus cicilan">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada pembayaran yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if(!$isPaid)
        <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header payment-modal-header">
                        <div>
                            <div class="small text-white-50 text-uppercase fw-semibold">Transaksi Rental</div>
                            <h5 class="modal-title text-white fw-bold" id="paymentModalLabel">Catat Pembayaran</h5>
                            <div class="small text-white-50">{{ $rentals->no_inv }}
                                · {{ $rentals->cust->name ?? '-' }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('manager.pembayaran.bayar', $rentals->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-4">
                            @if($errors->any())
                                <div class="alert alert-danger">{{ $errors->first() }}</div>
                            @endif
                            <div class="alert alert-primary d-flex justify-content-between align-items-center">
                                <div><small class="d-block">Sisa tagihan yang harus dibayar</small><strong class="fs-5">Rp {{ number_format($remaining,0,',','.') }}</strong>
                                </div>
                                <div class="text-end"><small class="d-block">Pembayaran berikutnya</small><strong
                                        class="fs-5">Ke-{{ $nextInstallment }}</strong></div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="payment-input-label">Total uang masuk sebelumnya</label>
                                    <input type="text" class="form-control"
                                           value="Rp {{ number_format((int)$rentals->nominal_in,0,',','.') }}" readonly>
                                    <input type="hidden" name="nominal_in" value="{{ (int)$rentals->nominal_in }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="payment-input-label">Nominal pembayaran <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="pay_debts" id="pay_debts" required
                                           inputmode="numeric" onkeyup="formatRupiah(this)"
                                           placeholder="Contoh: 500.000">
                                    <small class="text-muted">Tidak boleh melebihi sisa tagihan.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="payment-input-label">Tanggal pembayaran <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="date_pay"
                                           value="{{ now()->format('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="payment-input-label">Bank / Metode pembayaran</label>
                                    <select class="form-select" name="bank_id">
                                        <option value="">Pilih bank / metode</option>
                                        @foreach($bank as $banks)
                                            <option
                                                value="{{ $banks->id }}">{{ $banks->name }}{{ $banks->code ? ' ('.$banks->code.')' : '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="payment-input-label">Nama penerima</label>
                                    <input type="text" class="form-control" name="penerima" maxlength="255"
                                           placeholder="Nama penerima pembayaran">
                                </div>
                                <div class="col-md-6">
                                    <label class="payment-input-label">Diskon tambahan</label>
                                    <input type="text" class="form-control" name="diskon" value="0"
                                           onkeyup="formatRupiah2(this)" inputmode="numeric">
                                </div>
                                <div class="col-md-6">
                                    <label class="payment-input-label">Fee</label>
                                    <input type="text" class="form-control" name="fee" value="{{ (int)$rentals->fee }}"
                                           onkeyup="formatRupiah2(this)" inputmode="numeric">
                                </div>
                                <div class="col-md-6">
                                    <label class="payment-input-label">Keterangan</label>
                                    <input type="text" class="form-control" name="description" maxlength="1000"
                                           placeholder="Catatan pembayaran (opsional)">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal
                            </button>
                            <button type="submit" class="btn btn-primary px-4"><i class="bx bx-check-circle me-1"></i>
                                Simpan Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalElement = document.getElementById('paymentModal');
                if (modalElement && window.bootstrap) {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            });
        </script>
    @endif
@endsection
@push('head')

@endpush
@push('js')
    <script>
        window.formatRupiah = function(elementOrValue){
            let value = typeof elementOrValue === 'object' && elementOrValue !== null ? elementOrValue.value : elementOrValue;

            value = String(value ?? '').replace(/[^0-9]/g,'');
            if(!value) value = '0';

            let number = parseInt(value) || 0;
            let formatted = number.toLocaleString('id-ID');

            if(typeof elementOrValue === 'object' && elementOrValue !== null){
                elementOrValue.value = formatted;
            }

            return 'Rp ' + formatted;
        };
    </script>
@endpush
