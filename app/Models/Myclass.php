<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Myclass extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'kelas_id',
        'status',
        'foto',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
