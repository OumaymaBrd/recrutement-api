<?php

namespace App\Services;

use App\Repositories\AnnonceRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;

class AnnonceService
{
    protected $annonceRepository;

    public function __construct(AnnonceRepositoryInterface $annonceRepository)
    {
        $this->annonceRepository = $annonceRepository;
    }

    public function getAllAnnonces()
    {
        return $this->annonceRepository->all();
    }

    public function getAnnonceById($id)
    {
        return $this->annonceRepository->find($id);
    }

    public function createAnnonce(array $data, $user)
    {
        if (!$user->isRecruteur() && !$user->isAdmin()) {
            throw new AuthorizationException('Vous n\'êtes pas autorisé à créer une annonce.');
        }

        $data['user_id'] = $user->id;
        return $this->annonceRepository->create($data);
    }

    public function updateAnnonce($id, array $data, $user)
    {
        $annonce = $this->annonceRepository->find($id);

        if ($annonce->user_id !== $user->id && !$user->isAdmin()) {
            throw new AuthorizationException('Vous n\'êtes pas autorisé à modifier cette annonce.');
        }

        return $this->annonceRepository->update($id, $data);
    }

    public function deleteAnnonce($id, $user)
    {
        $annonce = $this->annonceRepository->find($id);

        if ($annonce->user_id !== $user->id && !$user->isAdmin()) {
            throw new AuthorizationException('Vous n\'êtes pas autorisé à supprimer cette annonce.');
        }

        return $this->annonceRepository->delete($id);
    }
}