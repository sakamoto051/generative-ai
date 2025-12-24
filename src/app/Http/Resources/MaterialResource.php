<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
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
            'material_code' => $this->material_code,
            'name' => $this->name,
            'category' => $this->category,
            'unit' => $this->unit,
            'standard_price' => (float) $this->standard_price,
            'lead_time' => (float) $this->lead_time,
            'minimum_order_quantity' => (float) $this->minimum_order_quantity,
            'safety_stock' => (float) $this->safety_stock,
            'is_lot_managed' => (bool) $this->is_lot_managed,
            'has_expiry_management' => (bool) $this->has_expiry_management,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
