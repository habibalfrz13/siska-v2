@extends('dashboard.main')

@section('content')
    <div class="row p-2">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Show User</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('user.index') }}">Back</a>
            </div>
        </div>
    </div>

    <div class="card border-info shadow-lg">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {{ $user->name }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <strong>Username:</strong>
                        {{ $user->username }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <strong>Email:</strong>
                        {{ $user->email }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <strong>Jenis Kelamin:</strong>
                        {{ $user->jenis_kelamin ?: 'Not specified' }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <strong>Bio:</strong>
                        {{ $user->bio ?: 'Not specified' }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <strong>Nomor Telepon:</strong>
                        {{ $user->nomor_hp ?: 'Not specified' }}
                    </div>
                </div>

                <div class="col-md-12 text-center">
                    <div class="form-group">
                        <strong>Foto:</strong>
                        @if($user->foto)
                            <img src="{{ Storage::url('user/' . $user->foto) }}" class="img-fluid rounded" alt="User Photo" style="max-width: 100%; max-height: 200px;">
                        @else
                            <div class="text-muted">No photo available</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
