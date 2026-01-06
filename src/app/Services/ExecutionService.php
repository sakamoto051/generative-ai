<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\ManufacturingExecution;
use App\Models\ManufacturingOrder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ExecutionService
{
    /**
     * Record manufacturing progress and update side effects (inventory, MO status).
     */
    public function reportProgress(ManufacturingOrder $mo, array $data): ManufacturingExecution
    {
        return DB::transaction(function () use ($mo, $data) {
            // 1. Record execution
            $execution = $mo->executions()->create([
                'good_quantity' => $data['good_quantity'],
                'scrap_quantity' => $data['scrap_quantity'] ?? 0,
                'actual_duration' => $data['actual_duration'] ?? null,
                'operator_id' => $data['operator_id'],
                'reported_at' => $data['reported_at'] ?? now(),
            ]);

            // 2. Update Product Inventory
            $inventory = Inventory::firstOrCreate(
                [
                    'item_id' => $mo->product_id,
                    'item_type' => Product::class,
                ],
                [
                    'quantity' => 0,
                ]
            );
            $inventory->increment('quantity', $data['good_quantity']);

            // 3. Update MO Status
            $this->updateMoStatus($mo);

            return $execution;
        });
    }

    /**
     * Determine and update the status of the Manufacturing Order based on its executions.
     */
    protected function updateMoStatus(ManufacturingOrder $mo): void
    {
        $mo->refresh();
        $totalGood = $mo->executions()->sum('good_quantity');

        if ($totalGood >= $mo->quantity) {
            $mo->update(['status' => 'Completed']);
        } elseif ($mo->status === 'Planned' || $mo->status === 'Released') {
            $mo->update(['status' => 'In Progress']);
        }
    }
}