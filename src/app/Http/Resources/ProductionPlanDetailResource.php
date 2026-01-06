<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionPlanDetailResource extends JsonResource
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
            'production_plan_id' => $this->production_plan_id,
            'product_id' => $this->product_id,
            'product_code' => $this->product->product_code ?? null,
            'product_name' => $this->product->name ?? null,
            'quantity' => (float) $this->quantity,
            'due_date' => $this->due_date,
            'priority' => $this->priority,
            'remarks' => $this->remarks,
        ];
    }
}