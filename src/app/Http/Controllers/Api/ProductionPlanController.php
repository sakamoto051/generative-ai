<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductionPlanRequest;
use App\Http\Requests\UpdateProductionPlanRequest;
use App\Http\Resources\ProductionPlanResource;
use App\Models\ProductionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = ProductionPlan::with('creator')->paginate(20);
        return ProductionPlanResource::collection($plans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductionPlanRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $plan = ProductionPlan::create(array_merge(
                $request->validated(),
                ['created_by' => auth()->id(), 'status' => 'Draft']
            ));

            foreach ($request->validated()['details'] as $detailData) {
                $plan->details()->create($detailData);
            }

            return (new ProductionPlanResource($plan->load('details.product')))
                ->response()
                ->setStatusCode(201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductionPlan $productionPlan)
    {
        return new ProductionPlanResource($productionPlan->load('details.product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductionPlanRequest $request, ProductionPlan $productionPlan)
    {
        return DB::transaction(function () use ($request, $productionPlan) {
            $productionPlan->update($request->validated());

            if ($request->has('details')) {
                // For simplicity in this track, we replace all details if provided
                $productionPlan->details()->delete();
                foreach ($request->validated()['details'] as $detailData) {
                    $productionPlan->details()->create($detailData);
                }
            }

            return new ProductionPlanResource($productionPlan->load('details.product'));
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionPlan $productionPlan)
    {
        if ($productionPlan->status !== 'Draft') {
            return response()->json(['message' => 'Only draft plans can be deleted'], 403);
        }

        $productionPlan->delete();

        return response()->json(null, 204);
    }

    public function submit(ProductionPlan $productionPlan)
    {
        return $this->updateStatus($productionPlan, 'Draft', 'Pending');
    }

    public function approve(ProductionPlan $productionPlan)
    {
        return $this->updateStatus($productionPlan, 'Pending', 'Approved');
    }

    public function reject(ProductionPlan $productionPlan)
    {
        return $this->updateStatus($productionPlan, 'Pending', 'Rejected');
    }

    public function cancel(ProductionPlan $productionPlan)
    {
        $productionPlan->update(['status' => 'Canceled']);
        return new ProductionPlanResource($productionPlan->load('details.product'));
    }

    protected function updateStatus(ProductionPlan $plan, string $fromStatus, string $toStatus)
    {
        if ($plan->status !== $fromStatus) {
            return response()->json([
                'message' => "Invalid status transition from {$plan->status} to {$toStatus}"
            ], 422);
        }

        $plan->update(['status' => $toStatus]);

        return new ProductionPlanResource($plan->load('details.product'));
    }
}