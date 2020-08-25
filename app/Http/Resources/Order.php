<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'orders',
            'id' => $this->id,
            'attributes' => [
                'user_id' => $this->user_id,
                'text' => $this->text,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ],
            'links' => [
                'self' => route('orders.show', $this->id)
            ]
        ];
    }
}
