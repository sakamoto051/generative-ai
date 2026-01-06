<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MoResource extends JsonResource
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
            'mo_number' => $this->mo_number,
            'product_id' => $this->product_id,
            'product_code' => $this->product->product_code ?? null,
            'product_name' => $this->product->name ?? null,
            'quantity' => (float) $this->quantity,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'components' => MoComponentResource::collection($this->whenLoaded('components')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}