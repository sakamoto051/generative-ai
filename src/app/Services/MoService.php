<?php

namespace App\Services;

use App\Models\ManufacturingOrder;
use App\Models\ProductionPlanDetail;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MoService
{
    /**
     * Generate a Manufacturing Order from a Production Plan Detail.
     */
    public function generateFromPlanDetail(ProductionPlanDetail $detail, User $creator): ManufacturingOrder
    {
        return DB::transaction(function () use ($detail, $creator) {
            $mo = ManufacturingOrder::create([
                'mo_number' => $this->generateMoNumber(),
                'production_plan_detail_id' => $detail->id,
                'product_id' => $detail->product_id,
                'quantity' => $detail->quantity,
                'due_date' => $detail->due_date,
                'status' => 'Planned',
                'created_by' => $creator->id,
            ]);

            // Snapshot BOM components
            $bomComponents = $detail->product->components;

            foreach ($bomComponents as $bom) {
                // Determine unit from child model
                $child = $bom->child;
                $unit = $child ? $child->unit : 'pcs';

                $mo->components()->create([
                    'item_id' => $bom->child_id,
                    'item_type' => $bom->child_type,
                    'required_quantity' => $bom->quantity * $detail->quantity,
                    'unit' => $unit,
                ]);
            }

            return $mo->load('components');
        });
    }

    /**
     * Generate a unique MO number.
     * Format: MO-YYYYMMDD-XXXX
     */
    public function generateMoNumber(): string
    {
        $date = now()->format('Ymd');
        $todayMoCount = ManufacturingOrder::where('mo_number', 'like', "MO-{$date}-%")->count();
        
        $nextNumber = $todayMoCount + 1;
        
        // Ensure uniqueness if deletion occurred
        while (ManufacturingOrder::where('mo_number', sprintf('MO-%s-%04d', $date, $nextNumber))->exists()) {
            $nextNumber++;
        }

        return sprintf('MO-%s-%04d', $date, $nextNumber);
    }
}