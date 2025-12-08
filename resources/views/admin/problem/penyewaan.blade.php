<div class="modal fade" id="penyewaan{{$data->id}}" tabindex="-1"
     aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Penyewa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class=" overflow-auto" id="prinpenyewa{{$data->id}}">
                <div class="form-container">
                    <div class="header">
                        <table class="penyewatable">
                            <tr>
                                <th colspan="3" class="penyewath text-center">PENYEWAAN</th>
                            </tr>
                            <tr>
                                <td rowspan="4"
                                    style="border-left:0.5px solid #aaa; border-bottom:0.5px solid #aaa;  width: 120px; vertical-align: middle;">
                                    <img src="{{asset('images/logodnd.png')}}"
                                         class="print-img"
                                         alt="dnd logo"
                                         style="width: 100%; height: 100px; object-fit: contain;">
                                </td>
                                <td style="padding: 6px; vertical-align: middle; padding-left: 15px; white-space: nowrap;">
                                    <strong style="font-size: 12px;">Komplek Sukamenak Indah Blok R N0.11</strong>
                                </td>
                                <td class="text-center"
                                    style="border: none; padding-left: 15px; white-space: nowrap; border-right:0.5px solid #aaa; border-left:0.5px solid #aaa; border-bottom:0.5px solid #aaa;">
                                    <strong style="font-size: 12px;">Tanggal/Bulan/Tahun</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 6px; vertical-align: top; padding-left: 15px; white-space: nowrap;">
                                    <strong style="font-size: 12px;">Sukamenak, Margahayu, Kabupaten Bandung
                                        40227</strong>
                                </td>
                                <td style="border: none; padding-left: 15px; white-space: nowrap; border-bottom:0.5px solid #aaa; border-right:0.5px solid #aaa; border-left:0.5px solid #aaa;"
                                    rowspan="3">
                                    <strong style="font-size: 12px;"></strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 6px; vertical-align: top; padding-left: 15px; white-space: nowrap;">
                                    <strong style="font-size: 12px;">Phone: 0821-2990-5005</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 6px; vertical-align: top; padding-left: 15px; white-space: nowrap; border-bottom:0.5px solid #aaa;">
                                    <strong style="font-size: 12px;">Email: dndsurvey90@gmail.com</strong>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <table class="penyewatable">
                        <tr>
                            <th colspan="6" class="text-start bg-opacity-50 ms-2 identitasth">
                                Identitas Penyewa
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-start bg-opacity-50 text-center identitasth">Penyewa</th>
                            <th colspan="3" class="text-start bg-opacity-50 text-center identitasth">Perushaan</th>
                        </tr>
                        <tr>
                            <td class="identitastd" style="border-left: 0.5px solid #aaa; padding: 6px; vertical-align: middle;" width="5%">
                                Penyewa
                            </td>
                            <td class="identitastd" style="vertical-align: middle" width="1%">:</td>
                            <td class="identitastd" width="40%">{{$data->rental->cust->name}}</td>
                            <td class="identitastd" style="border-left: 0.5px solid #aaa; padding: 6px" width="5%">Perusahaan</td>
                            <td class="identitastd" width="1%">:</td>
                            <td class="identitastd" style="border-right: 0.5px solid #aaa">{{$data->name_company}}</td>
                        </tr>
                        <tr>
                            <td class="identitastd" style="border-left: 0.5px solid #aaa; padding: 6px; vertical-align: middle;" width="5%">
                                Alamat
                            </td>
                            <td class="identitastd" style="vertical-align: middle" width="1%">:</td>
                            <td class="identitastd" width="40%">{{$data->rental->cust->addres}}</td>
                            <td class="identitastd" style="border-left: 0.5px solid #aaa; padding: 6px" width="5%">Alamat </td>
                            <td class="identitastd" width="1%">:</td>
                            <td class="identitastd" style="border-right: 0.5px solid #aaa">{{$data->addres_company}}</td>
                        </tr>
                        <tr>
                            <td class="identitastd" style="border-left: 0.5px solid #aaa; padding: 6px; vertical-align: middle;" width="5%">
                                Identitas
                            </td>
                            <td class="identitastd" style="vertical-align: middle" width="1%">:</td>
                            <td class="identitastd" width="40%">{{$data->rental->cust->no_identity}}</td>
                            <td class="identitastd" style="border-left: 0.5px solid #aaa; padding: 6px" width="5%">No. Telp.</td>
                            <td class="identitastd" width="1%">:</td>
                            <td class="identitastd" style="border-right: 0.5px solid #aaa">{{$data->phone_company}}</td>
                        </tr>
                        <tr>
                            <td class="identitastd" style="border-left: 0.5px solid #aaa; padding: 6px; vertical-align: middle;; border-bottom: 0.5px solid #aaa"
                                width="5%">No. Telp
                            </td>
                            <td class="identitastd" style="vertical-align: middle; border-bottom: 0.5px solid #aaa" width="1%">:</td>
                            <td class="identitastd" style="border-bottom: 0.5px solid #aaa;" width="40%">{{$data->rental->cust->phone}}</td>
                            <td class="identitastd" style="border-left: 0.5px solid #aaa; padding: 6px; border-bottom: 0.5px solid #aaa"
                                width="5%">No. PO
                            </td>
                            <td class="identitastd" style="border-bottom: 0.5px solid #aaa;" width="1%">:</td>
                            <td class="identitastd" style="border-bottom: 0.5px solid #aaa; border-right: 0.5px solid #aaa">{{$data->no_po}}</td>
                        </tr>
                        <tr>
                            <td style="font-size:8px; border-left: 0.5px solid #aaa; border-bottom: 0.5px solid #aaa; border-right: 0.5px solid #aaa"
                                colspan="3"><span style="color: red">(*Wajib)</span>
                            </td>
                            <td style="font-size:8px; border-left: 0.5px solid #aaa; border-bottom: 0.5px solid #aaa; border-right: 0.5px solid #aaa"
                                colspan="3"><span style="color: red">(*Jika atas nama perusahaan, identitas yang dicantumkan adalah KTP yang
                                bertanggung jawab</span>
                            </td>
                        </tr>
                    </table>

                    <table class="checkbox-grid penyewatable">
                        <tr>
                            <th colspan="2" class="alatth">Alat yang Disewa</th>
                        </tr>
                        <tr>
                            <td class="alattd" style="border-left: 0.5px solid #aaa; padding: 6px; vertical-align: top;"><input
                                    type="checkbox"> Total Station
                            </td>
                            <td class="alattd" style="border-right: 0.5px solid #aaa; "><input type="checkbox"> Echosounder</td>

                        </tr>
                        <tr>
                            <td class="alattd" style="border-left: 0.5px solid #aaa; padding: 6px; vertical-align: top;"><input
                                    type="checkbox"> Thedolite
                            </td>
                            <td class="alattd" style="border-right: 0.5px solid #aaa; "><input type="checkbox"> GPS Handheld</td>

                        </tr>
                        <tr>
                            <td class="alattd" style="border-left: 0.5px solid #aaa; padding: 6px; vertical-align: top;"><input
                                    type="checkbox"> Waterpass
                            </td>
                            <td class="alattd" style="border-right: 0.5px solid #aaa; "><input type="checkbox">
                                GPS RTK
                            </td>
                        </tr>
                        <tr>
                            <td class="alattd" style="border-left: 0.5px solid #aaa; padding: 6px; vertical-align: top;"><input
                                    type="checkbox"> GPS Geodetik
                            </td>
                            <td class="alattd" style="border-right: 0.5px solid #aaa; "><input type="checkbox">
                                Controller
                            </td>
                        </tr>
                    </table>

                    <table class="identitastable">

                        <tr>
                            <th class="alatth">Keterangan (Merk/Type/Unit)</th>
                            <th class="alatth">Keterangan (Unit)</th>
                        </tr>
                        <tr>
                            <td style="height:70px;" class="alattd">
                                <ul class="multi-column-list">
                                @php
                                    $itemIds = json_decode($data->item_id);
                                    $no = 1;
                                @endphp

                                @if(is_array($itemIds))
                                    @foreach($itemIds as $itemId)
                                        @php
                                            $item = \App\Models\Item::find($itemId);
                                        @endphp
                                        <li>{{ $item ? $item->name : 'Item not found' }}
                                            ({{ $item ? $item->no_seri : 'Item not found' }})
                                        </li>

                                    @endforeach
                                @else

                                    {{ $itemIds }}
                                @endif
                                </ul>
                            </td>
                            <td class="alattd">
                                <ul class="multi-column-list">
                                    @foreach($data->rental->accessoriescategory as $asdf)
                                        <li>
                                            {{ $asdf->accessory ? $asdf->accessory->name : 'Not Found' }}
                                            ({{ $asdf->accessories_quantity }})
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                        </tr>
                    </table>
                    @if(!empty($data->keterangan_item) || !empty($data->keterangan_acces))
                    <table class="penyewatable">
                            <tr>
                                <th  class="syaratth"><strong>Keterangan Penukaran</strong></th>
                            </tr>
                            <tr>
                                <td class="syarattd">
                                    @if(!empty($data->keterangan_item))
                                        <li>{{ $data->keterangan_item }}</li>
                                    @endif

                                    @if(!empty($data->keterangan_acces))
                                        <li>{{ $data->keterangan_acces }}</li>
                                    @endif
                                </td>
                            </tr>

                    </table>
                    @endif
                    <table class="penyewatable">
                        <tr>
                            <th class="syaratth">Lama Sewa</th>
                        </tr>
                        <tr>
                            <td class="syarattd">
                                Sewa alat selama {{$data->days_difference}} Hari,
                                Terhitung mulai tanggal {{formatId($data->date_start)}} s.d.
                                tanggal {{formatId($data->date_end)}}_
                            </td>
                        </tr>
                    </table>

                    <table class="penyewatable">
                        <tr>
                            <th class="syaratth" colspan="2">Persyaratan Sewa/Rental Alat</th>
                        </tr>
                        <tr>
                            <th class="syarattd">Instansi/Perusahaan</th>
                            <th class="syarattd">Perorangan</th>
                        </tr>
                        <tr>
                            <td class="syarattd">
                                1. KTP Penyewa (Yang bertanggung jawab)<span style="color: red">*</span><br>
                                2. PO yang sudah ditandatangani dan distempel oleh Instansi/Perusahaan<br>
                                3. DP (Down Payment)<span style="color: red">*</span><br>
                                4. Harus ada Identitas yang disimpan<span style="color: red">*</span>
                            </td>
                            <td class="syarattd">
                                1. KTP Penyewa<span style="color: red">*</span><br>
                                2. Rujukan Pemberi Pekerjaan*<br>
                                3. Kartu Keluarga / Surat Nikah<span style="color: red">**</span><br>
                                4. DP (Down Payment)<span style="color: red">*</span><br>
                                5. Harus ada Identitas yang disimpan*
                            </td>
                        </tr>
                    </table>

                    <div class="signatures">
                        <div>
                            <p><strong>DND Survey</strong></p>
                            <br><br><br>
                            <p>(_________________)</p>
                        </div>
                        <div>
                            <p><strong>Tanda Tangan Penyewa</strong></p>
                            <br><br><br>
                            <p>(_________________)</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="printSuratPenyewa('prinpenyewa{{$data->id}}')">
                    Print
                </button>
            </div>
        </div>
    </div>
</div>

@push('head')
    <style>
        .penyewaanbody {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #000;
            margin: 20px;
        }

        .form-container {
            border: 2px solid #f37021;
            padding: 20px;
            border-radius: 5px;
            max-width: 850px;
            margin: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #f37021;
            margin-bottom: 15px;
            padding-bottom: 5px;
        }

        .header h2 {
            color: #f37021;
            margin: 0;
        }

        .info {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }

        .info div {
            width: 48%;
        }

        .penyewatable {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .identitastable {
            width: 100%; /* tetap menyesuaikan lebar halaman */
            border-collapse: collapse; /* rapatkan border */
            font-size: 11px; /* perkecil ukuran teks */
            table-layout: fixed; /* mencegah sel melebar berlebihan */
        }

        .identitasth {
            border: 1px solid #aaa;
            padding: 6px;
            vertical-align: top;
            background-color: #f37021 !important; /* bg-secondary bg-opacity-50 */
            -webkit-print-color-adjust: exact; /* Forcing color to be printed */
            color-adjust: exact;
            color: white;
            font-size: 11px/* Forcing color to be printed in Firefox */
        }

        .identitastd {
            padding: 6px;
            vertical-align: middle;
            font-size: 10px;
        }

        .penyewath {
            border: 1px solid #aaa;
            padding: 6px;
            vertical-align: top;
            background-color: #f37021 !important; /* bg-secondary bg-opacity-50 */
            -webkit-print-color-adjust: exact; /* Forcing color to be printed */
            color-adjust: exact;
            color: white; /* Forcing color to be printed in Firefox */
        }

        .syaratth {
            border: 1px solid #aaa;
            padding: 6px;
            vertical-align: top;
            background-color: #f37021 !important; /* bg-secondary bg-opacity-50 */
            -webkit-print-color-adjust: exact; /* Forcing color to be printed */
            color-adjust: exact;
            color: white;
            font-size: 11px/* Forcing color to be printed in Firefox */
        }

        .alatth {
            border: 1px solid #aaa;
            padding: 6px;
            vertical-align: top;
            background-color: #f37021 !important; /* bg-secondary bg-opacity-50 */
            -webkit-print-color-adjust: exact; /* Forcing color to be printed */
            color-adjust: exact;
            color: white;
            font-size: 11px/* Forcing color to be printed in Firefox */
        }

        .penyewatd {
            border: 1px solid #aaa;
            padding: 6px;
            vertical-align: top;
        }

        .syarattd {
            border: 1px solid #aaa;
            padding: 6px;
            vertical-align: top;
            font-size: 10px;
        }

        .alattd {
            border: 1px solid #aaa;
            padding: 6px;
            vertical-align: top;
            font-size: 10px;
        }

        .checkbox-grid td {
            width: 20%;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .signatures div {
            text-align: center;
            width: 45%;
        }
        .multi-column-list {
            display: grid;
            grid-template-rows: repeat(5, auto); /* Maksimal 5 item per kolom */
            grid-auto-flow: column; /* Setelah 5 baris, lanjut ke kolom baru */
            gap: 4px 20px; /* jarak antar baris dan antar kolom */
            padding-left: 20px; /* indentasi seperti <ul> biasa */
            margin: 0;
        }
    </style>
@endpush
@push('js')
    <script>
        function printSuratPenyewa(modalId) {
            var printContents = document.getElementById(modalId).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload(); // Reload untuk mengembalikan tampilan asli
        }
    </script>
@endpush
