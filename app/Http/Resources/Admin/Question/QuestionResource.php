<?php

namespace App\Http\Resources\Admin\Question;

use App\Http\Resources\Admin\Language\LanguageResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class QuestionResource extends JsonResource
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
            'category' => new QuestionCategoryResource($this->category),
            'is_image_option' => $this->is_image_option,
            'options' => QuestionOptionResource::collection($this->options),
            'src' => $this->src,
            'language' => new LanguageResource($this->language),
            'difficulty' => $this->difficulty,
        ];
    }
}
