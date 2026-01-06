<?php

namespace App\Http\Resources;

use App\Models\Material;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MrpCalculationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $itemType = $this['item_type'];
        $itemId = $this['item_id'];
        $item = $itemType::find($itemId);

        return [
            'item_id' => $itemId,
            'item_type' => $itemType === Product::class ? 'product' : 'material',
            'item_code' => $item ? ($itemType === Product::class ? $item->product_code : $item->material_code) : null,
            'item_name' => $item ? $item->name : 'Unknown',
            'total_requirement' => (float) $this['total_requirement'],
            'inventory_applied' => (float) $this['inventory_applied'],
            'net_requirement' => (float) $this['net_requirement'],
        ];
    }
}
