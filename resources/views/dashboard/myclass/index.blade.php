@extends('dashboard.main')

@section('content')
<div class="container-fluid">
    <div class="container-fluid">
    <div class="card mb-0">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4 d-inline">Kelas Management</h5>
                </div>
                <div class="col d-flex justify-content-end">
                    <a href="{{ route('kelas.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus"></i> Buat Kelas</a>
                </div>
            </div>
            <table class="table table-striped" style="width:100%" id="example">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Waktu Pelaksanaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kelas as $kelas)
                        @if($kelas->status == 'Aktif')
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $kelas->judul }}</td>
                                <td>{{ \Carbon\Carbon::parse($kelas->pelaksanaan)->format('d M Y') }}</td>
                                <td>
                                    <a class="btn btn-sm btn-info" href="{{ route('myclass.show', $kelas->id) }}">Detail</a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection
