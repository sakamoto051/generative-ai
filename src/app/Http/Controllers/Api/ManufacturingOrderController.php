<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MoResource;
use App\Models\ManufacturingOrder;
use App\Models\ProductionPlan;
use App\Services\MoService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManufacturingOrderController extends Controller
{
    use AuthorizesRequests;

    protected MoService $moService;

    public function __construct(MoService $moService)
    {
        $this->moService = $moService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = ManufacturingOrder::with('product')->paginate(20);
        return MoResource::collection($orders);
    }

    /**
     * Display the specified resource.
     */
    public function show(ManufacturingOrder $manufacturingOrder)
    {
        return new MoResource($manufacturingOrder->load(['product', 'components.item']));
    }
}