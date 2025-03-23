<?php

namespace App\Repositories;

interface AnnonceRepositoryInterface extends BaseRepositoryInterface
{
    public function findByUser($userId);
}