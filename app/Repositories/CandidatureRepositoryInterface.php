<?php

namespace App\Repositories;

interface CandidatureRepositoryInterface extends BaseRepositoryInterface
{
    public function findByUser($userId);
    public function findByAnnonce($annonceId);
    public function findByUserAndAnnonce($userId, $annonceId);
    public function updateStatut($id, $statut);
    public function countAll();
public function countByStatut($statut);
}