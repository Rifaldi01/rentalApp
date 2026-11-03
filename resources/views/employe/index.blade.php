@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="ms-2 mt-2">
            <h5>Stok Alat <i class="bx bx-box"></i></h5>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Alat</p>
                            <h4 class="my-1 text-warning">{{$item}}</h4>
                            <p class="mb-0 font-13">Semua Alat</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i
                                class='bx bxs-box'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @foreach ($itemsByCategory as $item)
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{$item->cat->name}}</p>
                                <h4 class="my-1 text-warning">{{ $item->available }} Available</h4>
                                <p class="mb-0 font-13">Of {{ $item->total }}</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i
                                    class='bx bx-box'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Perawatan Alat</p>
                            <h4 class="my-1 text-success">{{$maintenance}}</h4>
                            <p class="mb-0 font-13">Perikasa Alat Rusak</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i
                                class='bx bx-box'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('head')

@endpush
@push('js')

@endpush
