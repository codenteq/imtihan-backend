<?php

namespace App\Http\Resources\Admin\Condition;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ConditionResource extends JsonResource
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
            'question_category_id' => $this->question_category_id,
            'condition_category_id' => $this->condition_category_id,
            'value' => $this->value,
            'is_active' => $this->is_active,
        ];
    }
}
