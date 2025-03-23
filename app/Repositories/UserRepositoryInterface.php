<?php

namespace App\Repositories;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail($email);
    public function countAll();
public function countByRole($role);
}