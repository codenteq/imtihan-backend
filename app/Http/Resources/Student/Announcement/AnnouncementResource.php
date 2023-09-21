<?php

namespace App\Http\Resources\Student\Announcement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
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
            'content' => $this->content,
            'src' => $this->src,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
