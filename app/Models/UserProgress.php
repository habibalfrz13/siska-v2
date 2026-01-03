<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    use HasFactory;

    protected $table = 'user_progress';

    protected $fillable = [
        'user_id',
        'material_id',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns this progress
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the material for this progress
     */
    public function material()
    {
        return $this->belongsTo(CourseMaterial::class, 'material_id');
    }

    /**
     * Check if this progress is completed
     */
    public function isCompleted()
    {
        return !is_null($this->completed_at);
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted()
    {
        $this->completed_at = now();
        $this->save();
    }

    /**
     * Get or create progress for a user and material
     */
    public static function getOrCreate($userId, $materialId)
    {
        return static::firstOrCreate([
            'user_id' => $userId,
            'material_id' => $materialId,
        ]);
    }
}
