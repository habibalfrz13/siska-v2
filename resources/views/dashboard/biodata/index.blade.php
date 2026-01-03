@extends('dashboard.main')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <div class="col-md-12 grid-margin">
                            <div class="row">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-10">
                                            <h4>Users Management</h4>
                                        </div>
                                        <div class="col-lg-2 text-right">
                                            <a class="btn btn-success text-white" href="{{ route('user.create') }}">Create User</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table">
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th width="300px">Action</th>
                                </tr>
                                @foreach ($data as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <a class="btn btn-info" href="{{ route('user.show', $user->id) }}">Show</a>
                                            <a class="btn btn-primary" href="{{ route('user.edit', $user->id) }}">Edit</a>
                                            <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                                onclick="return confirm('Yakin Untuk Mengapus Data ?')" class="d-inline">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- {!! $data->render() !!} --}}
@endsection
