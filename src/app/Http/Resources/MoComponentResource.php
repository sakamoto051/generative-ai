<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MoComponentResource extends JsonResource
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
            'item_id' => $this->item_id,
            'item_type' => $this->item_type,
            'item_code' => $this->item->product_code ?? $this->item->material_code ?? null,
            'item_name' => $this->item->name ?? null,
            'required_quantity' => (float) $this->required_quantity,
            'unit' => $this->unit,
        ];
    }
}