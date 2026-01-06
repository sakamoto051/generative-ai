<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BomTreeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'code' => $this['code'],
            'name' => $this['name'],
            'type' => $this['type'],
            'quantity' => $this['quantity'] ?? null,
            'total_quantity' => $this['total_quantity'] ?? null,
            'children' => BomTreeResource::collection(collect($this['children'])),
        ];
    }
}
