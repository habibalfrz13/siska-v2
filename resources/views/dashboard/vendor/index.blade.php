@extends('dashboard.main')

@section('content')
    <div class="container-fluid">
        <div class="container-fluid">
            <div class="card mb-0 ">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col">
                            <h5 class="card-title fw-semibold mb-4 d-inline">Data Vendor</h5>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <a href="{{ route('vendor.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus"></i>
                                Tambah</a>
                        </div>
                    </div>
                    <table class="table table-striped" style="width:100%" id="example">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Vendor</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vendors as $vendor)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $vendor->vendor }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-info" href="{{ route('vendor.show', $vendor->id) }}">Show</a>
                                        <a class="btn btn-sm btn-primary" href="{{ route('vendor.edit', $vendor->id) }}">Edit</a>
                                        <form action="{{ route('vendor.destroy', $vendor->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?')" class="d-inline">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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
