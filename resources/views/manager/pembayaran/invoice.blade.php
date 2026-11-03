<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $rentals->no_inv }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 25px;
            background: #f1f3f5;
            color: #263238;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
        }

        .invoice {
            max-width: 850px;
            margin: auto;
            padding: 38px;
            background: #fff;
            border: 1px solid #e5e7eb;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            padding-bottom: 22px;
            border-bottom: 3px solid #ff8402;
        }

        .company-name {
            font-size: 23px;
            font-weight: 800;
            color: #ff8402;
            margin-bottom: 6px;
        }

        .invoice-title {
            font-size: 27px;
            font-weight: 800;
            color: #ff8402;
            text-align: right;
        }

        .invoice-number {
            text-align: right;
            margin-top: 7px;
            line-height: 1.7;
        }

        .section {
            margin-top: 25px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 800;
            color: #ff8402;
            text-transform: uppercase;
            border-bottom: 1px solid #d9e0e7;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .customer-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px 35px;
        }

        .label {
            color: #7b8794;
            font-size: 11px;
            margin-bottom: 4px;
        }

        .value {
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th {
            background: #f8ede2;
            color: #344054;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }

        th, td {
            padding: 10px;
            border: 1px solid #dfe5eb;
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            width: 350px;
            max-width: 100%;
            margin: 20px 0 0 auto;
        }

        .summary td {
            padding: 9px 10px;
        }

        .summary .grand-total td {
            background: #f8ede2;
            color: #ff8402;
            font-size: 15px;
            font-weight: 800;
        }

        .status {
            display: inline-block;
            padding: 7px 15px;
            border-radius: 5px;
            font-weight: 800;
            font-size: 12px;
        }

        .paid {
            color: #087443;
            background: #dcfce7;
        }

        .unpaid {
            color: #9a6700;
            background: #fef3c7;
        }

        .payment-note {
            margin-top: 20px;
            padding: 13px;
            border: 1px solid #dfe5eb;
            background: #fbf7f2;
        }

        .footer {
            margin-top: 45px;
            display: flex;
            justify-content: space-between;
            gap: 30px;
            text-align: center;
        }

        .signature {
            width: 200px;
            padding-top: 10px;
        }

        .signature-space {
            height: 65px;
        }

        .print-actions {
            max-width: 850px;
            margin: 0 auto 15px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .print-actions button {
            border: 0;
            padding: 10px 17px;
            border-radius: 6px;
            cursor: pointer;
            color: white;
            background: #ff8402;
        }

        .print-actions .back {
            background: #64748b;
        }

        @media print {
            @page {
                size: A4;
                margin: 12mm;
            }

            body {
                padding: 0;
                background: white;
                font-size: 11px;
            }

            .invoice {
                max-width: none;
                padding: 0;
                border: 0;
            }

            .print-actions {
                display: none !important;
            }

            table, tr, .section, .payment-note {
                page-break-inside: avoid;
            }
        }

        @media screen and (max-width: 600px) {
            body {
                padding: 10px;
            }

            .invoice {
                padding: 18px;
            }

            .top {
                flex-direction: column;
            }

            .invoice-title,
            .invoice-number {
                text-align: left;
            }

            .customer-grid {
                grid-template-columns: 1fr;
            }

            .footer {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

<div class="print-actions">
    <button class="back" onclick="window.close()">Kembali</button>
    <button onclick="window.print()">
        Cetak / Simpan PDF
    </button>
</div>

<div class="invoice">

    {{-- HEADER --}}
    <div class="top">
        <div>
            <img src="{{asset('images/logodnd.png')}}"
                                           class="print-img"
                                           alt="dnd logo"
                                           style="width: 60%; height: 100px; object-fit: contain; margin-top:-22px;">
            <div>Invoice tagihan penyewaan</div>
            <div style="margin-top:8px;color:#7b8794">
                Terima kasih telah menggunakan layanan kami.
            </div>
        </div>

        <div>
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">
                <strong>{{ $rentals->no_inv ?: '-' }}</strong><br>
                Tanggal:
                {{ $rentals->tgl_inv ? formatId($rentals->tgl_inv) : '-' }}
            </div>
        </div>
    </div>

    {{-- CUSTOMER --}}
    <div class="section">
        <div class="section-title">Informasi Customer</div>

        <div class="customer-grid">
            <div>
                <div class="label">Nama Customer</div>
                <div class="value">{{ $rentals->cust->name ?? 'Customer dihapus' }}</div>
            </div>

            <div>
                <div class="label">Nomor Telepon</div>
                <div class="value">{{ $rentals->cust->phone ?? '-' }}</div>
            </div>

            <div>
                <div class="label">Nomor PO</div>
                <div class="value">{{ $rentals->no_po ?: '-' }}</div>
            </div>

            <div>
                <div class="label">Periode Rental</div>
                <div class="value">
                    {{ $rentals->date_start ? formatId($rentals->date_start) : '-' }}
                    s/d
                    {{ $rentals->date_end ? formatId($rentals->date_end) : '-' }}
                </div>
            </div>
        </div>
    </div>

    {{-- ITEM --}}
    <div class="section">
        <div class="section-title">Daftar Item Rental</div>

        <table>
            <thead>
            <tr>
                <th width="45">No</th>
                <th>Nama Item</th>
                <th>Nomor Seri</th>
            </tr>
            </thead>
            <tbody>
            @forelse($items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->name ?? '-' }}</td>
                    <td>{{ $item->no_seri ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Tidak ada item.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        @if($rentals->keterangan_item)
            <div style="margin-top:8px">
                <strong>Catatan:</strong> {{ $rentals->keterangan_item }}
            </div>
        @endif
    </div>

    {{-- ACCESSORIES --}}
    <div class="section">
        <div class="section-title">Daftar Accessories Rental</div>

        <table>
            <thead>
            <tr>
                <th width="45">No</th>
                <th>Nama Accessories</th>

                <th class="text-center">Jumlah</th>
            </tr>
            </thead>
            <tbody>
            @forelse($accessories as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $row->accessory->name ?? 'Accessories dihapus' }}</td>

                    <td class="text-center">{{ $row->accessories_quantity ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada accessories.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        @if($rentals->keterangan_acces)
            <div style="margin-top:8px">
                <strong>Catatan:</strong> {{ $rentals->keterangan_acces }}
            </div>
        @endif
    </div>

    {{-- SUMMARY --}}
    <div class="section">
        <div class="section-title">Ringkasan Tagihan</div>

        <table class="summary">
            <tr>
                <td>Total Invoice</td>
                <td class="text-right">
                    Rp {{ number_format((int)$rentals->total_invoice, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td>Diskon</td>
                <td class="text-right">
                    Rp {{ number_format((int)$rentals->diskon, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td>Fee</td>
                <td class="text-right">
                    Rp {{ number_format((int)$rentals->fee, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td>Total Sudah Dibayar</td>
                <td class="text-right">
                    Rp {{ number_format($totalPaid, 0, ',', '.') }}
                </td>
            </tr>
            <tr class="grand-total">
                <td>SISA TAGIHAN</td>
                <td class="text-right">
                    Rp {{ number_format($remaining, 0, ',', '.') }}
                </td>
            </tr>
        </table>

        <div style="margin-top:15px;text-align:right">
            Status Pembayaran:

            @if($isPaid)
                <span class="status paid">LUNAS</span>
            @else
                <span class="status unpaid">BELUM LUNAS</span>
            @endif
        </div>
    </div>

    {{-- PAYMENT HISTORY --}}
    <div class="section">
        <div class="section-title">Riwayat Pembayaran / Cicilan</div>

        <table>
            <thead>
            <tr>
                <th width="45">No</th>
                <th>Tanggal Bayar</th>
                <th>Bank / Metode</th>
                <th>Penerima</th>
                <th>Keterangan</th>
                <th class="text-right">Nominal</th>
            </tr>
            </thead>
            <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td class="text-center">
                        {{ $payment->installment_no ?: $loop->iteration }}
                    </td>
                    <td>
                        {{ $payment->date_pay ? formatId($payment->date_pay) : '-' }}
                    </td>
                    <td>
                        {{ $payment->bank->name ?? ($payment->description ? 'Lainnya' : '-') }}
                    </td>
                    <td>{{ $payment->penerima ?: '-' }}</td>
                    <td>{{ $payment->description ?: '-' }}</td>
                    <td class="text-right">
                        Rp {{ number_format((int)$payment->pay_debts, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">
                        Belum ada pembayaran cicilan.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="payment-note">
        <strong>Catatan:</strong>
        Dokumen ini merupakan rincian tagihan rental berdasarkan data transaksi
        dan pembayaran yang tercatat di sistem.
    </div>

    {{-- SIGNATURE --}}
    <div class="footer">
        <div class="signature">
            <strong>Customer</strong>
            <div class="signature-space"></div>
            <div>________________________</div>
        </div>

        <div class="signature">
            <strong>Hormat Kami</strong>
            <div class="signature-space"></div>
            <div>________________________</div>
        </div>
    </div>

</div>

</body>
</html>
