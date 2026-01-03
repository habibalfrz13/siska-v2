<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kelas_id',
        'myclass_id',
        'peserta_id',
        'jumlah_pembayaran',
        'status_pembayaran',
        'snap_token',
        'tanggal_pembayaran',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function myclass()
    {
        return $this->belongsTo(Myclass::class, 'myclass_id');
    }

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }
}
