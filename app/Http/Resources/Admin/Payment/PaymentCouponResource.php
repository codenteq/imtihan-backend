<?php

namespace App\Http\Resources\Admin\Payment;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class PaymentCouponResource extends JsonResource
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
            'code' => $this->code,
            'discount' => $this->discount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
