<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MrpCalculationResource;
use App\Services\MrpService;
use Illuminate\Http\Request;

class MrpController extends Controller
{
    /**
     * Calculate MRP requirements for a given product and quantity.
     */
    public function calculate(Request $request, MrpService $mrpService)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $results = $mrpService->calculateRequirements(
            (int) $validated['product_id'],
            (float) $validated['quantity']
        );

        return MrpCalculationResource::collection($results);
    }
}