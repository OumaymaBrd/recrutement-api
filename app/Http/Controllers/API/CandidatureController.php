<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CandidatureService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CandidatureController extends Controller
{
    protected $candidatureService;
    protected $notificationService;

    public function __construct(
        CandidatureService $candidatureService,
        NotificationService $notificationService
    ) {
        $this->candidatureService = $candidatureService;
        $this->notificationService = $notificationService;
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $user = request()->user();
        
        if ($user->isCandidat()) {
            $candidatures = $this->candidatureService->getCandidaturesByUser($user->id);
        } else {
            // Pour les recruteurs, on récupère toutes les candidatures de leurs annonces
            // Cette logique pourrait être déplacée dans le service
            $candidatures = [];
            foreach ($user->annonces as $annonce) {
                $candidatures = array_merge(
                    $candidatures, 
                    $this->candidatureService->getCandidaturesByAnnonce($annonce->id, $user)->toArray()
                );
            }
        }
        
        return response()->json($candidatures);
    }

    public function show($id)
    {
        try {
            $candidature = $this->candidatureService->getCandidatureById($id, request()->user());
            return response()->json($candidature);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'annonce_id' => 'required|exists:annonces,id',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'lettre_motivation' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $candidature = $this->candidatureService->createCandidature($request->all(), $request->user());
            return response()->json($candidature, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function destroy($id)
    {
        try {
            $this->candidatureService->deleteCandidature($id, request()->user());
            return response()->json(['message' => 'Candidature supprimée avec succès']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function updateStatut(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'statut' => 'required|in:en_attente,en_cours,acceptee,refusee',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $candidature = $this->candidatureService->updateStatut($id, $request->statut, $request->user());
            
            // Notifier le candidat du changement de statut
            $this->notificationService->notifyCandidatureStatusChange($id, $request->statut);
            
            return response()->json($candidature);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function getMesCandidatures()
    {
        $user = request()->user();
        
        if (!$user->isCandidat()) {
            return response()->json(['message' => 'Seuls les candidats peuvent accéder à cette ressource'], 403);
        }
        
        $candidatures = $this->candidatureService->getCandidaturesByUser($user->id);
        return response()->json($candidatures);
    }

    public function getCandidaturesByAnnonce($annonceId)
    {
        try {
            $candidatures = $this->candidatureService->getCandidaturesByAnnonce($annonceId, request()->user());
            return response()->json($candidatures);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function notifyCandidatureStatusChange($id)
{
    try {
        $notification = $this->notificationService->notifyCandidatureStatusChange($id, null);
        
        return response()->json([
            'message' => 'Notification envoyée avec succès',
            'notification' => $notification
        ]);
    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 403);
    }
}
}