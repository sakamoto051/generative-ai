<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBomRequest;
use App\Http\Requests\UpdateBomRequest;
use App\Http\Resources\BomResource;
use App\Models\Bom;

class BomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BomResource::collection(Bom::with(['parent', 'child'])->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBomRequest $request)
    {
        $bom = Bom::create($request->validated());

        return (new BomResource($bom->load(['parent', 'child'])))
            ->additional(['message' => 'BOM relationship created successfully'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bom $bom)
    {
        return new BomResource($bom->load(['parent', 'child']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBomRequest $request, Bom $bom)
    {
        $bom->update($request->validated());

        return (new BomResource($bom->load(['parent', 'child'])))
            ->additional(['message' => 'BOM relationship updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bom $bom)
    {
        $bom->delete();

        return response()->json(null, 204);
    }
}
