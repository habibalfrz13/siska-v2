<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'title',
        'description',
        'order',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Get the kelas that owns this module
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Get all materials in this module
     */
    public function materials()
    {
        return $this->hasMany(CourseMaterial::class, 'module_id')->orderBy('order');
    }

    /**
     * Get published materials only
     */
    public function publishedMaterials()
    {
        return $this->hasMany(CourseMaterial::class, 'module_id')
            ->where('is_published', true)
            ->orderBy('order');
    }

    /**
     * Get total duration of all materials in minutes
     */
    public function getTotalDurationAttribute()
    {
        return $this->materials()->sum('duration') ?? 0;
    }

    /**
     * Get materials count
     */
    public function getMaterialsCountAttribute()
    {
        return $this->materials()->count();
    }

    /**
     * Scope for published modules
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope ordered by order column
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
