<?php

namespace App\Http\Resources\Student\Note;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     */
    public function toArray($request): array|\JsonSerializable|Arrayable
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'is_everyone' => $this->is_everyone,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
