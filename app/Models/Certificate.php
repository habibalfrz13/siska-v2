<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kelas_id',
        'certificate_number',
        'issued_at',
        'completion_date',
        'pdf_path',
        'is_valid',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'completion_date' => 'date',
        'is_valid' => 'boolean',
    ];

    /**
     * Get the user that owns this certificate
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the kelas for this certificate
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Generate unique certificate number
     * Format: SISKA-YEAR-RANDOM
     */
    public static function generateCertificateNumber()
    {
        $year = date('Y');
        $random = strtoupper(Str::random(5));
        $number = "SISKA-{$year}-{$random}";

        // Ensure uniqueness
        while (self::where('certificate_number', $number)->exists()) {
            $random = strtoupper(Str::random(5));
            $number = "SISKA-{$year}-{$random}";
        }

        return $number;
    }

    /**
     * Get formatted issue date
     */
    public function getFormattedIssueDateAttribute()
    {
        return $this->issued_at->translatedFormat('d F Y');
    }

    /**
     * Get verification URL
     */
    public function getVerificationUrlAttribute()
    {
        return route('certificates.verify', $this->certificate_number);
    }

    /**
     * Check if certificate exists for user and class
     */
    public static function existsFor($userId, $kelasId)
    {
        return self::where('user_id', $userId)
            ->where('kelas_id', $kelasId)
            ->exists();
    }

    /**
     * Get certificate for user and class
     */
    public static function getFor($userId, $kelasId)
    {
        return self::where('user_id', $userId)
            ->where('kelas_id', $kelasId)
            ->first();
    }
}
