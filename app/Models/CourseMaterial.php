<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'type',
        'content',
        'file_path',
        'video_url',
        'duration',
        'order',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'duration' => 'integer',
    ];

    /**
     * Material types
     */
    const TYPE_VIDEO = 'video';
    const TYPE_TEXT = 'text';
    const TYPE_FILE = 'file';
    const TYPE_LINK = 'link';

    /**
     * Get the module that owns this material
     */
    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    /**
     * Get all user progress for this material
     */
    public function userProgress()
    {
        return $this->hasMany(UserProgress::class, 'material_id');
    }

    /**
     * Check if a specific user has completed this material
     */
    public function isCompletedByUser($userId)
    {
        return $this->userProgress()
            ->where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->exists();
    }

    /**
     * Get progress for a specific user
     */
    public function getProgressForUser($userId)
    {
        return $this->userProgress()->where('user_id', $userId)->first();
    }

    /**
     * Get formatted duration (e.g., "5 menit")
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) {
            return null;
        }
        
        if ($this->duration >= 60) {
            $hours = floor($this->duration / 60);
            $mins = $this->duration % 60;
            return $mins > 0 ? "{$hours} jam {$mins} menit" : "{$hours} jam";
        }
        
        return "{$this->duration} menit";
    }

    /**
     * Get icon class based on material type
     */
    public function getTypeIconAttribute()
    {
        return match($this->type) {
            self::TYPE_VIDEO => 'bi-play-circle',
            self::TYPE_TEXT => 'bi-file-text',
            self::TYPE_FILE => 'bi-file-earmark-arrow-down',
            self::TYPE_LINK => 'bi-link-45deg',
            default => 'bi-file',
        };
    }

    /**
     * Get type label in Indonesian
     */
    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            self::TYPE_VIDEO => 'Video',
            self::TYPE_TEXT => 'Materi Teks',
            self::TYPE_FILE => 'File Download',
            self::TYPE_LINK => 'Link Eksternal',
            default => 'Materi',
        };
    }

    /**
     * Scope for published materials
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
