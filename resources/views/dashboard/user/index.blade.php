@extends('dashboard.main')

@section('content')
<div class="container-fluid">
    <div class="card mb-0">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col">
                    <h4 class="card-title">Users Management</h4>
                </div>
                <div class="col d-flex justify-content-end">
                    <a href="{{ route('user.create') }}" class="btn btn-success text-white">Create User</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped" id="example">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <a class="btn btn-info" href="{{ route('user.show', $user->id) }}">Show</a>
                                <a class="btn btn-primary" href="{{ route('user.edit', $user->id) }}">Edit</a>
                                <form action="{{ route('user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin Untuk Mengapus Data ?')" class="d-inline">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
