<?php

namespace App\Http\Resources\Admin\Question;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class QuestionOptionResource extends JsonResource
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
            'description' => $this->description,
            'is_correct' => $this->is_correct,
            'question' => new QuestionResource($this->whenLoaded('question')),
            'src' => $this->src,
        ];
    }
}
