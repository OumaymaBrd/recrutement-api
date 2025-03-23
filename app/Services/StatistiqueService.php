<?php

namespace App\Services;

use App\Repositories\AnnonceRepositoryInterface;
use App\Repositories\CandidatureRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\User;
use App\Models\Candidature;

class StatistiqueService
{
    protected $annonceRepository;
    protected $candidatureRepository;
    protected $userRepository;

    public function __construct(
        AnnonceRepositoryInterface $annonceRepository,
        CandidatureRepositoryInterface $candidatureRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->annonceRepository = $annonceRepository;
        $this->candidatureRepository = $candidatureRepository;
        $this->userRepository = $userRepository;
    }

    public function getRecruteurStats($userId)
    {
        $user = $this->userRepository->find($userId);
        
        if (!$user->isRecruteur() && !$user->isAdmin()) {
            throw new AuthorizationException('Vous n\'êtes pas autorisé à accéder à ces statistiques.');
        }
        
        $annonces = $this->annonceRepository->findByUser($userId);
        $annonceIds = $annonces->pluck('id')->toArray();
        
        $stats = [
            'total_annonces' => count($annonces),
            'candidatures_par_statut' => [
                'en_attente' => 0,
                'en_cours' => 0,
                'acceptee' => 0,
                'refusee' => 0,
            ],
        ];
        
        foreach ($annonceIds as $annonceId) {
            $candidatures = $this->candidatureRepository->findByAnnonce($annonceId);
            
            foreach ($candidatures as $candidature) {
                $stats['candidatures_par_statut'][$candidature->statut]++;
            }
        }
        
        return $stats;
    }

    public function getGlobalStats($user)
    {
        if (!$user->isAdmin()) {
            throw new AuthorizationException('Seuls les administrateurs peuvent accéder aux statistiques globales.');
        }
        
        $stats = [
            'total_utilisateurs' => $this->userRepository->countAll(),
            'total_recruteurs' => $this->userRepository->countByRole('recruteur'),
            'total_candidats' => $this->userRepository->countByRole('candidat'),
            'total_annonces' => $this->annonceRepository->all()->count(),
            'total_candidatures' => $this->candidatureRepository->countAll(),
            'candidatures_par_statut' => [
                'en_attente' => $this->candidatureRepository->countByStatut('en_attente'),
                'en_cours' => $this->candidatureRepository->countByStatut('en_cours'),
                'acceptee' => $this->candidatureRepository->countByStatut('acceptee'),
                'refusee' => $this->candidatureRepository->countByStatut('refusee'),
            ],
        ];
        
        return $stats;
    }
}