<?php

namespace App\Repositories;

use App\Models\Annonce;

class AnnonceRepository extends BaseRepository implements AnnonceRepositoryInterface
{
    public function __construct(Annonce $model)
    {
        parent::__construct($model);
    }

    public function findByUser($userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }
}