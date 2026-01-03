<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISKAE - Print Peserta</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('template/dashboard') }}/images/logos/4.svg" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
            background-color: #f5f5f5;
            -webkit-print-color-adjust: exact; /* Mempertahankan warna saat mencetak di Chrome */
        }

        .container {
            max-width: 800px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin-bottom: 5px;
            font-size: 32px;
            color: #007bff;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
            color: #343a40; /* Warna abu-abu yang lebih gelap */
            font-weight: normal;
            text-transform: uppercase;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            border-radius: 15px 15px 0 0;
        }

        .card-title {
            color: #fff;
            font-size: 24px;
            margin: 0;
            padding: 20px 0;
            text-align: center;
            text-transform: uppercase;
        }

        .table {
            background-color: #fff;
            border-radius: 0 0 15px 15px;
        }

        .table th {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
        }

        .table td {
            font-size: 16px;
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="header">
            <h1>SISKAE</h1>
            <h2>Sistem Informasi Sertifikasi dan Kompetensi Keahlian</h2>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Data Transaksi</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama Pengguna</th>
                                <th scope="col">Kelas</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksis as $trx)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ optional($trx->user->biodata)->username ?? 'N/A' }}</td>
                                    <td>{{ $trx->kelas->judul }}</td>
                                    <td>{{ $trx->jumlah_pembayaran }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-start"><strong>Total</strong></td>
                                <td><strong>{{ $totalPembayaran }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
