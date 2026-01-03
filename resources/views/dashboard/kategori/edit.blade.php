@extends('dashboard.main')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-left">
                                <h2>Edit Kategori</h2>
                            </div>
                            <div class="pull-right">
                                <a class="btn btn-primary" href="{{ route('kategori.index') }}">Back</a>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('kategori.update', $kategori->id) }}" enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                    
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nama_kategori"><strong>Nama Kategori:</strong></label>
                                    <input type="text" name="nama_kategori" value="{{ $kategori->nama_kategori }}" placeholder="Nama Kategori" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12 text-center mt-2">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>                    
                </div>
            </div>
        </div>
    </div>
@endsection
