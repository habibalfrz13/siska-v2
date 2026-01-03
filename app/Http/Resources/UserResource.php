<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * User API Resource
 * 
 * Transforms User model for API responses
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->id_role === 1 ? 'admin' : 'user',
            'email_verified_at' => $this->when(
                $this->email_verified_at,
                $this->email_verified_at?->toIso8601String()
            ),
            'biodata' => new BiodataResource($this->whenLoaded('biodata')),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
