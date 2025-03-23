<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\StatistiqueService;
use Illuminate\Http\Request;

class StatistiqueController extends Controller
{
    protected $statistiqueService;

    public function __construct(StatistiqueService $statistiqueService)
    {
        $this->statistiqueService = $statistiqueService;
        $this->middleware('auth:sanctum');
    }

    public function getRecruteurStats()
    {
        try {
            $stats = $this->statistiqueService->getRecruteurStats(request()->user()->id);
            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function getGlobalStats()
    {
        try {
            $stats = $this->statistiqueService->getGlobalStats(request()->user());
            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }
}