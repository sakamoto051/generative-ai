<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'product_code' => $this->product_code,
            'name' => $this->name,
            'category' => $this->category,
            'unit' => $this->unit,
            'standard_cost' => (float) $this->standard_cost,
            'standard_manufacturing_time' => (float) $this->standard_manufacturing_time,
            'lead_time' => (float) $this->lead_time,
            'safety_stock' => (float) $this->safety_stock,
            'reorder_point' => (float) $this->reorder_point,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
