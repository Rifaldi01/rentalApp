<div class="modal fade" id="exampleExtraLargeModal{{$data->id}}" tabindex="-1"
     aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Surat Jalan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="invoice overflow-auto" id="prinsurat{{$data->id}}">
                <div class="modal-body modal-surat">
                    <table>
                        <tr>
                            <td rowspan="4" style="border: none; width: 120px; vertical-align: middle;">
                                <img src="{{asset('images/logodnd.png')}}"
                                     class="print-img"
                                     alt="dnd logo"
                                     style="width: 100%; height: 100px; object-fit: contain;">
                            </td>
                            <td style="border: none; padding-left: 15px; white-space: nowrap;">
                                <strong style="font-size: 12px; color:#fbd4b3;">
                                    Komplek Sukamenak Indah Blok R N0.11
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; padding-left: 15px; white-space: nowrap;">
                                <strong style="font-size: 12px; color:#fbd4b3;">Sukamenak, Margahayu, Kabupaten Bandung 40227</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; padding-left: 15px; white-space: nowrap;">
                                <strong style="font-size: 12px; color:#fbd4b3;">Phone: 0821-2990-5005</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; padding-left: 15px; white-space: nowrap;">
                                <strong style="font-size: 12px; color:#fbd4b3;">Email:  dndsurvey90@gmail.com</strong>
                            </td>
                        </tr>
                    </table>
                    {{--                    <div class="text-center">--}}
                    {{--                        <img src="{{asset('images/logo/'. $data->divisi->logo)}}" alt="" width="10%" class="img-surat">--}}
                    {{--                        <div class="mt-1"><strong style="font-size: 13px;">Komplek--}}
                    {{--                                Sukamenak Indah Blok Q90 Kopo - Sayati, Kabupaten Bandung,</strong>--}}
                    {{--                        </div>--}}
                    {{--                        <div class="mt-1"><strong style="font-size: 13px;">Website : dndsurvey.id |--}}
                    {{--                                Email : admin@dndsurvey.id</strong>--}}
                    {{--                        </div>--}}
                    {{--                        <div class="mt-1"><strong style="font-size: 13px;">Kantor . 022 - 5442 0354--}}
                    {{--                                /Phone. 0821-2990-0025 / 081-2992-5005</strong>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    <hr style="border: 3px solid #fbd4b3">
                    <div class="mb-3" style="font-size: 12px">
                        <strong>Bandung,</strong> {{formatId($data->updated_at)}}
                    </div>
                    <div class="table-responsive mb-4">
                        <table class="" style="width:100%">
                            <thead>
                            <tr>
                                <th colspan="6" class="text-end bg-secondary bg-opacity-50 sjg" style="font-size: 13px;">
                                    BERITA ACARA BARANG MASUK
                                </th>
                            </tr>
                            <tr>
                                <th width="4%">Kepada</th>
                                <th width="1%">:</th>
                                <th>{{optional($data->cust)->name ?? '-'}}</th>
                                <th width="1%">No</th>
                                <th width="1%" class="text-end">:</th>
                                <th width="1%" class="text-end"
                                    style="border-right-width:0;">BABM/DND/...../...../...../</th>
                            </tr>
                            <tr>
                                <th>Periode</th>
                                <th>:</th>
                                <th>{{formatId($data->date_start)}} - {{formatId($data->date_end)}}</th>
                                <th width="1%" class="text-end">Perihal</th>
                                <th width="1%" class="text-end">:</th>
                                <th width="1%" style="border-right-width:0;">Pengembalian Rental</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered border-pdf">
                            <thead>
                            <tr>
                                <th class="text-center" width="1%" style="border-left-width:1px;">No</th>
                                <th class="text-center">Nama Barang</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">No Seri</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $itemIds = json_decode($data->item_id);
                                $no = 1;
                            @endphp

                            @if(is_array($itemIds))
                                @foreach($itemIds as $itemId)
                                    @php
                                        $item = \App\Models\Item::find($itemId);
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>{{ $item ? $item->name : 'Item not found' }}</td>
                                        <td>1</td>
                                        <td>{{ $item ? $item->no_seri : 'Item not found' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">{{ $itemIds }}</td>
                                </tr>
                            @endif

                            @foreach($data->accessoriescategory as $asdf)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ $asdf->accessory ? $asdf->accessory->name : 'Not Found' }}</td>
                                    <td>{{ $asdf->accessories_quantity + $asdf->kembali}}</td>
                                    <td>Aksesoris</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="mt-2">
                        <table width="100%">
                            <thead>
                            <tr>
                                <th class="text-center">Yang Menerima,</th>
                                <th class="text-center">Bagian Umum,</th>
                                <th class="text-center" style="border-right-width:0px;">Hormat Kami,</th>
                            </tr>
                            </thead>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td style="border-right-width:0px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td style="border-right-width:0px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td style="border-right-width:0px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="text-center">(...........................)</td>
                                <td class="text-center">(...........................)</td>
                                <td class="text-center" style="border-right-width:0px;">(...........................)
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="printSuratJalan('prinsurat{{$data->id}}')">
                    Print
                </button>
                <button type="button"
                        class="btn btn-primary"
                        onclick="downloadSuratJalan('prinsurat{{ $data->id }}')">
                    Download PDF
                </button>


            </div>
        </div>
    </div>
</div>

@push('head')
    <style>
        /* CSS khusus untuk print */
        @media print {

            @page {
                size: A4;
                margin: 0;
            }

            .table-style {
                width: 100%;
                margin-top: 4px;
                margin-bottom: 4px;
                border-bottom-width: 0;
            }

            .modal-dialog,
            .modal-dialog * {
                visibility: visible;
            }

            .modal-dialog {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }
            td{
                font-size: 12px;
            }
            th{
                font-size: 12px;
            }
        }

        th.sjg {
            background-color: #fbd4b3 !important; /* bg-secondary bg-opacity-50 */
            -webkit-print-color-adjust: exact; /* Forcing color to be printed */
            color-adjust: exact; /* Forcing color to be printed in Firefox */
        }

        .img-surat {
            width: 20%;
        }

        .modal-surat {
            padding: 15mm;
            font-size: 12px;
        }
        .border-pdf,
        .border-pdf th,
        .border-pdf td{
            border-top-width:1px;
            border-bottom-width:1px;
            border-right-width:1px;
            border-left-width:1px;
        }
    </style>
@endpush
@push('js')
    <!-- DOMPurify wajib -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.10/purify.min.js"></script>

    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        function printSuratJalan(modalId) {
            var printContents = document.getElementById(modalId).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload(); // Reload untuk mengembalikan tampilan asli
        }
    </script>
    <script>
        async function downloadSuratJalan(modalId) {
            const { jsPDF } = window.jspdf;

            const content = document.getElementById(modalId).innerHTML;

            const pdf = new jsPDF('p', 'pt', 'a4');

            await pdf.html(content, {
                callback: function (pdf) {
                    pdf.save("surat_jalan.pdf");
                },
                x: 10,
                y: 10,
                width: 575,
                windowWidth: 1200
            });
        }
    </script>


@endpush
