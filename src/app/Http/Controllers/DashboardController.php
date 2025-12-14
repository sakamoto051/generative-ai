<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ManufacturingOrder;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductionPlan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * ダッシュボードを表示
     *
     * @return View
     */
    public function index(): View
    {
        $data = [
            'productionPlansCount' => ProductionPlan::count(),
            'manufacturingOrdersCount' => ManufacturingOrder::count(),
            'productsCount' => Product::count(),
            'materialsCount' => Material::count(),
        ];

        return view('dashboard', $data);
    }
}
