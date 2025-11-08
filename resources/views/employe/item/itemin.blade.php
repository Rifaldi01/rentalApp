@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>No Seri</th>
                        <th>Keterangan</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ins as $key => $data)
                        <tr>
                            <td>{{$key +1}}</td>
                            <td>{{formatId($data->created_at)}}</td>
                            <td>{{$data->item->name}}</td>
                            <td>{{$data->item->no_seri}}</td>
                            <td>{{$data->description}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('head') @endpush
@push('js') @endpush
