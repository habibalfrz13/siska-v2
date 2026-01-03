@extends('dashboard.main')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-left">
                                <h2>Edit Biodata</h2>
                            </div>
                            <div class="pull-right">
                                <a class="btn btn-primary" href="{{ route('user.profile') }}">Back</a>
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('biodata.update', $biodata->id) }}" enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username"><strong>Username:</strong></label>
                                    <input type="text" name="username" value="{{ $biodata->username }}" placeholder="username" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="alamat"><strong>Alamat:</strong></label>
                                    <input type="text" name="alamat" value="{{ $biodata->alamat }}" placeholder="Alamat" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_telepon"><strong>Nomor Telepon:</strong></label>
                                    <input type="text" name="nomor_telepon" value="{{ $biodata->nomor_telepon }}" placeholder="Nomor Telepon" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bio"><strong>Bio:</strong></label>
                                    <input type="text" name="bio" value="{{ $biodata->bio }}" placeholder="bio" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ttl"><strong>Tanggal Lahir:</strong></label>
                                    <input type="date" name="ttl" value="{{ $biodata->ttl }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenis_kelamin"><strong>Jenis Kelamin:</strong></label>
                                    <select name="jenis_kelamin" class="form-control">
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="Laki-laki" {{ $biodata->jenis_kelamin === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ $biodata->jenis_kelamin === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="foto" class="form-label">Upload Foto Profile</label>
                                    <input type="file" class="form-control" id="foto" name="foto">
                                </div>
                            </div>
                            <div class="col-md-12 text-center mt-5">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>                    
                </div>
            </div>
        </div>
    </div>
@endsection
