<?php

namespace App\Services;

use App\Repositories\CandidatureRepositoryInterface;
use App\Repositories\AnnonceRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Storage;

class CandidatureService
{
    protected $candidatureRepository;
    protected $annonceRepository;

    public function __construct(
        CandidatureRepositoryInterface $candidatureRepository,
        AnnonceRepositoryInterface $annonceRepository
    ) {
        $this->candidatureRepository = $candidatureRepository;
        $this->annonceRepository = $annonceRepository;
    }

    public function getCandidaturesByUser($userId)
    {
        return $this->candidatureRepository->findByUser($userId);
    }

    public function getCandidaturesByAnnonce($annonceId, $user)
    {
        $annonce = $this->annonceRepository->find($annonceId);

        if ($annonce->user_id !== $user->id && !$user->isAdmin()) {
            throw new AuthorizationException('Vous n\'êtes pas autorisé à voir ces candidatures.');
        }

        return $this->candidatureRepository->findByAnnonce($annonceId);
    }

    public function createCandidature(array $data, $user)
    {
        if (!$user->isCandidat()) {
            throw new AuthorizationException('Seuls les candidats peuvent postuler.');
        }

        // Vérifier si l'utilisateur a déjà postulé à cette annonce
        $existingCandidature = $this->candidatureRepository->findByUserAndAnnonce(
            $user->id,
            $data['annonce_id']
        );

        if ($existingCandidature) {
            throw new \Exception('Vous avez déjà postulé à cette annonce.');
        }

        // Gérer l'upload du CV
        if (isset($data['cv']) && $data['cv']) {
            $cvPath = $data['cv']->store('cvs', 'public');
            $data['cv_path'] = $cvPath;
        }

        $data['user_id'] = $user->id;
        $data['statut'] = 'en_attente';

        return $this->candidatureRepository->create($data);
    }

    public function deleteCandidature($id, $user)
    {
        $candidature = $this->candidatureRepository->find($id);

        if ($candidature->user_id !== $user->id && !$user->isAdmin()) {
            throw new AuthorizationException('Vous n\'êtes pas autorisé à supprimer cette candidature.');
        }

        // Supprimer le fichier CV si nécessaire
        if ($candidature->cv_path) {
            Storage::disk('public')->delete($candidature->cv_path);
        }

        return $this->candidatureRepository->delete($id);
    }

    public function updateStatut($id, $statut, $user)
    {
        $candidature = $this->candidatureRepository->find($id);
        $annonce = $this->annonceRepository->find($candidature->annonce_id);

        if ($annonce->user_id !== $user->id && !$user->isAdmin()) {
            throw new AuthorizationException('Vous n\'êtes pas autorisé à modifier le statut de cette candidature.');
        }

        return $this->candidatureRepository->updateStatut($id, $statut);
    }

    public function getCandidatureById($id, $user)
    {
        $candidature = $this->candidatureRepository->find($id);
        
        // Vérifier si l'utilisateur est autorisé à voir cette candidature
        if ($candidature->user_id !== $user->id && 
            $candidature->annonce->user_id !== $user->id && 
            !$user->isAdmin()) {
            throw new AuthorizationException('Vous n\'êtes pas autorisé à voir cette candidature.');
        }
        
        return $candidature;
    }
}