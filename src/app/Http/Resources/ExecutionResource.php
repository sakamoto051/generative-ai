<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExecutionResource extends JsonResource
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
            'manufacturing_order_id' => $this->manufacturing_order_id,
            'good_quantity' => (float) $this->good_quantity,
            'scrap_quantity' => (float) $this->scrap_quantity,
            'actual_duration' => $this->actual_duration,
            'operator_id' => $this->operator_id,
            'operator_name' => $this->operator->name ?? null,
            'reported_at' => $this->reported_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}