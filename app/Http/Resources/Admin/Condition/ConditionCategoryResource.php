<?php

namespace App\Http\Resources\Admin\Condition;

use App\Http\Resources\Admin\Language\LanguageResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ConditionCategoryResource extends JsonResource
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
            'key' => $this->key,
            'language' => new LanguageResource($this->language),
        ];
    }
}
