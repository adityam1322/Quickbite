<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreCategorieRequest;
use App\Http\Requests\UpdateCategorieRequest;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(): JsonResponse
{
    $categorie = Categorie::query()
        ->latest()
        ->get();

    return response()->json([
        'Categorie' => $categorie,
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategorieRequest $request): JsonRespose
    {
        $categorie = Categorie::create(
        $request->validated());

        return response()->json([
          'message' => 'Categorie created successfully.',
          'Categorie' => $categorie,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Categorie $categorie): JsonResponse
    {
        $this->authorize('view', $categorie);

        return response()->json([
            'Categorie' => $categorie,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategorieRequest $request, Categorie $categorie):JsonResponse
    {
         $categorie->update(
            $request->validated()
        );

        return response()->json([
            'message' => 'Categorie updated successfully.',
            'Categorie' => $categorie->fresh(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categorie $categorie): JsonResponse
    {
        $this->authorize('delete', $categorie);

        $categorie->delete();

        return response()->json([
            'message' => 'Categorie deleted successfully.',
        ]);
    }
}
