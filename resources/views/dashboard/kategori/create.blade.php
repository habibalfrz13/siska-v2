@extends('dashboard.main')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-left">
                                <h2>Create New Kategori</h2>
                            </div>
                            <div class="pull-right">
                                <a class="btn btn-primary" href="{{ route('kategori.index') }}"> Back </a>
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('kategori.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mt-2">
                                    <strong>Nama Kategori:</strong>
                                    <input type="text" name="nama_kategori" placeholder="Nama Kategori" class="form-control">
                                    @error('nama_kategori')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>                    
                </div>
            </div>
        </div>
    </div>
@endsection
