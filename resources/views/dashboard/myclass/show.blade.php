@extends('dashboard.main')

@section('content')
    <div class="row p-2">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Show MyClass</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('myclass.index') }}">Back</a>
            </div>
        </div>
    </div>

    <div class="card border-info shadow-lg">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peserta</th>
                            <th>Status</th>
                            <th>Bukti Transfer</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($myclass as $kelas)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $kelas->nama_peserta }}</td>
                                <td>{{ $kelas->status }}</td>
                                <td>
                                    <div class="text-center"> <!-- Center the image -->
                                        @if($kelas->foto)
                                            <img src="{{ url('images/buktiTF/' . $kelas->foto) }}" class="img-fluid rounded" alt="User Photo" style="max-width: 100%; max-height: 100px;">
                                        @else
                                            <div class="text-muted">No Bukti Available</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($kelas->status == 'Pending')
                                        <form action="{{ route('myclass.update', $kelas->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success">Accept</button>
                                        </form>
                                    @elseif($kelas->status == 'Aktif')
                                        {{-- <a href="{{ route('myclass.show', $kelas->id) }}" class="btn btn-primary">Detail</a> --}}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
