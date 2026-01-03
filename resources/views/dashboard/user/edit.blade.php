@extends('dashboard.main')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-left">
                                <h2>Edit User</h2>
                            </div>
                            <div class="pull-right">
                                <a class="btn btn-primary" href="{{ route('user.index') }}">Back</a>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('user.update', $user->id) }}" enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                    
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name"><strong>Name:</strong></label>
                                    <input type="text" name="name" value="{{ $user->name }}" placeholder="Name" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenis_kelamin"><strong>Jenis Kelamin:</strong></label>
                                    <select name="jenis_kelamin" class="form-control">
                                        <option value="" {{ $user->jenis_kelamin === '' ? 'selected' : '' }}>-- Pilih Jenis Kelamin --</option>
                                        <option value="Laki-laki" {{ $user->jenis_kelamin === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ $user->jenis_kelamin === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bio"><strong>Bio:</strong></label>
                                    <input type="text" name="bio" value="{{ $user->bio }}" placeholder="Bio" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_hp"><strong>Nomor Telepon:</strong></label>
                                    <input type="text" name="nomor_hp" value="{{ $user->nomor_hp }}" placeholder="Nomor Telepon" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email"><strong>Email:</strong></label>
                                    <input type="text" name="email" value="{{ $user->email }}" placeholder="Email" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="foto"><strong>Upload Foto:</strong></label>
                                    <input type="file" name="foto" class="form-control">
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
