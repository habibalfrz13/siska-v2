<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biodata extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'username',
        'alamat',
        'nomor_telepon',
        'bio',
        'ttl',
        'jenis_kelamin',
        'foto',
    ];

    protected $dates = [
        'ttl',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
