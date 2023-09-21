<?php

namespace App\Http\Resources\Admin\Question;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class QuestionCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'children' => $this->whenLoaded('children'),
            'language_id' => $this->language_id,
        ];
    }
}
