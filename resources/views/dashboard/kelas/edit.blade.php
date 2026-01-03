@extends('dashboard.main')

@section('content')
    <div class="container-fluid">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row my-3">
                        <div class="col">
                            <a href="{{ route('kelas.index') }}" class="btn btn-info">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <h5 class="card-title fw-semibold mb-1 d-inline">Edit Kelas</h5>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('kelas.update', $kelas->id) }}">
                        @method('PATCH')
                        @csrf
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Judul:</strong>
                                    <input type="text" name="judul" value="{{ $kelas->judul }}" placeholder="Judul Kelas" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Kuota:</strong>
                                    <input type="number" name="kuota" value="{{ $kelas->kuota }}" placeholder="Kuota Kelas" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Pelaksanaan:</strong>
                                    <input type="date" name="pelaksanaan" value="{{ \Carbon\Carbon::parse($kelas->pelaksanaan)->format('Y-m-d') }}" class="form-control">
                                </div>
                            </div>                            
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Status:</strong>
                                    <select name="status" class="form-control">
                                        <option value="Aktif" {{ $kelas->status === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="Tidak Aktif" {{ $kelas->status === 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <button type="submit" class="btn btn-primary mt-2"><i class="bi bi-sd-card"></i> Simpan</button>
                            </div>
                        </div>
                    </form>    
                </div>
            </div>
        </div>
    </div>
@endsection
