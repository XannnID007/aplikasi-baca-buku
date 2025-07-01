<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RecommendationService;

class RecommendationController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Get personalized recommendations
     */
    public function getPersonalized(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $recommendations = $this->recommendationService->getPersonalizedRecommendations(auth()->id());

        return response()->json([
            'recommendations' => $recommendations,
            'total' => $recommendations->count()
        ]);
    }

    /**
     * Get recommendations by category
     */
    public function getByCategory(Request $request)
    {
        $request->validate([
            'kategori_ids' => 'required|array',
            'kategori_ids.*' => 'exists:kategoris,id'
        ]);

        $recommendations = $this->recommendationService->getRecommendationsByCategory(
            $request->kategori_ids,
            auth()->id()
        );

        return response()->json([
            'recommendations' => $recommendations,
            'total' => $recommendations->count()
        ]);
    }
}
