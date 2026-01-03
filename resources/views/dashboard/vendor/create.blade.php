@extends('dashboard.main')

@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="row">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="col-md-12 grid-margin">
                        <div class="row">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-10">
                                        <h4>Create New Vendor</h4>
                                    </div>
                                    <div class="col-lg-2 text-right">
                                        <a class="btn btn-primary mb-2" href="{{ route('vendor.index') }}"> Back </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('vendor.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="vendor">Nama Vendor:</label>
                                        <input type="text" name="vendor" class="form-control" placeholder="Nama Vendor">
                                    </div>
                                </div>
                                <div class="col-md-12 text-center mt-4">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
