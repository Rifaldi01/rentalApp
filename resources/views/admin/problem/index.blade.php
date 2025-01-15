@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="text-uppercase">List Problem</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th>INV</th>
                        <th>Name</th>
                        <th>Item</th>
                        <th>Accessories</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" width="18%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rental as $key => $data)
                        <tr id="rentalRow{{ $data->id }}">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $data->rental->no_inv ?? 'N/A' }}</td>
                            <td>{{ $data->rental->cust->name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $itemIds = json_decode($data->item_id);
                                @endphp
                                @if(is_array($itemIds))
                                    @foreach($itemIds as $itemId)
                                        @php
                                            $item = \App\Models\Item::find($itemId);
                                        @endphp
                                            <li>{{ $item ? $item->cat->name : null }}-{{ $item ? $item->no_seri : 'Item not found' }}</li>
                                    @endforeach
                                @else
                                    {{ $itemIds }}
                                @endif
                            </td>
                            <td>
                                @if($data->access)
                                    @foreach(explode(',', $data->access) as $accessory)
                                        <li>{{ $accessory }}</li>
                                    @endforeach
                                @else
                                    <li>No accessories</li>
                                @endif
                            </td>
                            <td>{{ formatId($data->date_start) }}</td>
                            <td>{{ formatId($data->date_end) }}</td>
                            <td class="text-center">
                                <span class="badge bg-danger">Problem</span>
                            </td>
                            <td>
                                <button class="btn btn-success lni lni-checkmark float-end finish-button"
                                        data-bs-toggle="modal" data-bs-target="#exampleVerticallycenteredModal{{ $data->id }}"
                                        title="Finished" id="finishButton{{ $data->id }}" style="display: none;">
                                </button>

                                <div class="modal fade" id="exampleVerticallycenteredModal{{ $data->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-center">Apakah Masalah Selesai?</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.problem.finis', $data->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Ya</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-dnd lni lni-eye float-end me-1" data-bs-toggle="modal"
                                        data-bs-target="#exampleLargeModal{{ $data->id }}" title="Detail"></button>

                                <div class="modal fade" id="exampleLargeModal{{ $data->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Problem</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <th>Name</th>
                                                            <td>{{ $data->rental->cust->name ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Item</th>
                                                            <td>{{ $data->rental->item->name ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Accessories</th>
                                                            <td>{{ $data->access }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Start Date</th>
                                                            <td>{{ dateId($data->date_start) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>End Date</th>
                                                            <td>{{ dateId($data->date_end) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Description</th>
                                                            <td><p class="text-danger">{{ $data->descript }}</p></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a href="https://api.whatsapp.com/send?phone=62{{ $data->rental->cust->phone ?? '' }}&text=Halo%20Customer%20yth,%20ada%20masalah%20dalam%20peminjaman%20anda%20segera%20konfirmasi%20kepada%20kami%20untuk%20menyelesaikan%20masalah%20tersebut"
                                   class="btn btn-success lni lni-whatsapp float-end me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Chat Customer">
                                </a>

                                <form action="{{ route('admin.problem.returned', $data->id) }}" method="POST" class="return-form" id="returnForm{{ $data->id }}">
                                    @csrf
                                    <button type="submit" class="btn btn-warning lni lni-package float-end me-1 return-button"
                                            id="returnButton{{ $data->id }}" title="Item Returned">
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet"/>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(".datepicker").flatpickr();

        document.addEventListener('DOMContentLoaded', function () {
            // Memeriksa localStorage untuk status tombol "Returned"
            document.querySelectorAll('.return-button').forEach(function (button) {
                const rentalId = button.id.replace('returnButton', '');

                // Cek jika tombol "Returned" sudah diklik sebelumnya
                if (localStorage.getItem('returned_' + rentalId) === 'true') {
                    button.style.display = 'none'; // Sembunyikan tombol "Returned"
                    document.getElementById('finishButton' + rentalId).style.display = 'inline-block'; // Tampilkan tombol "Finished"
                }
            });

            // Menangani event submit pada form
            document.querySelectorAll('.return-form').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault(); // Mencegah pengiriman form
                    const rentalId = this.id.replace('returnForm', '');
                    localStorage.setItem('returned_' + rentalId, 'true'); // Simpan status di localStorage
                    document.getElementById('returnButton' + rentalId).style.display = 'none'; // Sembunyikan tombol "Returned"
                    document.getElementById('finishButton' + rentalId).style.display = 'inline-block'; // Tampilkan tombol "Finished"
                    this.submit(); // Kirim form
                });
            });
        });
    </script>
@endpush
