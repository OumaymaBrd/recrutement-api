<?php

namespace App\Repositories;

use App\Models\Candidature;

class CandidatureRepository extends BaseRepository implements CandidatureRepositoryInterface
{
    public function __construct(Candidature $model)
    {
        parent::__construct($model);
    }

    public function findByUser($userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function findByAnnonce($annonceId)
    {
        return $this->model->where('annonce_id', $annonceId)->get();
    }

    public function findByUserAndAnnonce($userId, $annonceId)
    {
        return $this->model->where('user_id', $userId)
                          ->where('annonce_id', $annonceId)
                          ->first();
    }

    public function updateStatut($id, $statut)
    {
        $candidature = $this->find($id);
        $candidature->statut = $statut;
        $candidature->save();
        return $candidature;
    }

    public function countAll()
{
    return $this->model->count();
}

public function countByStatut($statut)
{
    return $this->model->where('statut', $statut)->count();
}
}