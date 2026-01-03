<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'kuota',
        'pelaksanaan',
        'status',
        'id_kategori',
        'id_vendor',
        'foto',
        'harga',
        'deskripsi',
    ];

    public function myClasses()
    {
        return $this->hasMany(Myclass::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get all course modules for this class
     */
    public function modules()
    {
        return $this->hasMany(CourseModule::class, 'kelas_id')->orderBy('order');
    }

    /**
     * Get published modules only
     */
    public function publishedModules()
    {
        return $this->hasMany(CourseModule::class, 'kelas_id')
            ->where('is_published', true)
            ->orderBy('order');
    }

    /**
     * Get total materials count across all modules
     */
    public function getTotalMaterialsAttribute()
    {
        return $this->modules()->withCount('materials')->get()->sum('materials_count');
    }

    /**
     * Get total duration in minutes
     */
    public function getTotalDurationAttribute()
    {
        $totalMinutes = 0;
        foreach ($this->modules as $module) {
            $totalMinutes += $module->materials()->sum('duration') ?? 0;
        }
        return $totalMinutes;
    }
}
