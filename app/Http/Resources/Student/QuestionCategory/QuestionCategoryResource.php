<?php

namespace App\Http\Resources\Student\QuestionCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionCategoryResource extends JsonResource
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
            'parents' => QuestionCategoryResource::collection($this->whenLoaded('childrenTree')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
