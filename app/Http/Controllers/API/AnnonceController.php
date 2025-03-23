<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AnnonceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnnonceController extends Controller
{
    protected $annonceService;

    public function __construct(AnnonceService $annonceService)
    {
        $this->annonceService = $annonceService;
        $this->middleware('auth:sanctum');
        $this->middleware('role:recruteur,admin')->except(['index', 'show']);
    }

    public function index()
    {
        $annonces = $this->annonceService->getAllAnnonces();
        return response()->json($annonces);
    }

    public function show($id)
    {
        $annonce = $this->annonceService->getAnnonceById($id);
        return response()->json($annonce);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'localisation' => 'required|string|max:255',
            'type_contrat' => 'required|string|max:255',
            'salaire' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $annonce = $this->annonceService->createAnnonce($request->all(), $request->user());
            return response()->json($annonce, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'localisation' => 'sometimes|required|string|max:255',
            'type_contrat' => 'sometimes|required|string|max:255',
            'salaire' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $annonce = $this->annonceService->updateAnnonce($id, $request->all(), $request->user());
            return response()->json($annonce);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function destroy($id)
    {
        try {
            $this->annonceService->deleteAnnonce($id, request()->user());
            return response()->json(['message' => 'Annonce supprimée avec succès']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }
}