<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MoResource;
use App\Http\Resources\ExecutionResource;
use App\Models\ManufacturingOrder;
use App\Models\ProductionPlan;
use App\Services\MoService;
use App\Services\ExecutionService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManufacturingOrderController extends Controller
{
    use AuthorizesRequests;

    protected MoService $moService;
    protected ExecutionService $executionService;

    public function __construct(MoService $moService, ExecutionService $executionService)
    {
        $this->moService = $moService;
        $this->executionService = $executionService;
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

    /**
     * Report manufacturing progress for an order.
     */
    public function execute(Request $request, ManufacturingOrder $manufacturingOrder)
    {
        $validated = $request->validate([
            'good_quantity' => 'required|numeric|min:0.01',
            'scrap_quantity' => 'nullable|numeric|min:0',
            'actual_duration' => 'nullable|integer|min:1',
            'reported_at' => 'nullable|date',
        ]);

        $execution = $this->executionService->reportProgress($manufacturingOrder, array_merge(
            $validated,
            ['operator_id' => auth()->id()]
        ));

        return new ExecutionResource($execution->load('operator'));
    }

    /**
     * Release manufacturing orders from a production plan.
     */
    public function release(ProductionPlan $productionPlan)
    {
        if ($productionPlan->status !== 'Approved') {
            return response()->json(['message' => 'Only approved plans can be released'], 422);
        }

        $orders = DB::transaction(function () use ($productionPlan) {
            $generatedOrders = [];
            foreach ($productionPlan->details as $detail) {
                // Check if MO already exists for this detail to prevent double-release
                if ($detail->manufacturingOrder()->exists()) {
                    continue;
                }
                $generatedOrders[] = $this->moService->generateFromPlanDetail($detail, auth()->user());
            }
            return $generatedOrders;
        });

        if (empty($orders)) {
            return response()->json(['message' => 'No new manufacturing orders were generated (already released or no details)'], 200);
        }

        $reloadedOrders = ManufacturingOrder::with(['product', 'components.item'])
            ->whereIn('id', collect($orders)->pluck('id'))
            ->get();

        return MoResource::collection($reloadedOrders);
    }

    /**
     * Update the status of a manufacturing order.
     */
    public function updateStatus(Request $request, ManufacturingOrder $manufacturingOrder)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Released,In Progress,Completed,Canceled',
        ]);

        try {
            $updatedOrder = $this->moService->updateStatus($manufacturingOrder, $validated['status']);
            return new MoResource($updatedOrder->load(['product', 'components.item']));
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}