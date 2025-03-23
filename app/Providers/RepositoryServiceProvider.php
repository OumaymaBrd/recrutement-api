<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\AnnonceRepositoryInterface;
use App\Repositories\AnnonceRepository;
use App\Repositories\CandidatureRepositoryInterface;
use App\Repositories\CandidatureRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AnnonceRepositoryInterface::class, AnnonceRepository::class);
        $this->app->bind(CandidatureRepositoryInterface::class, CandidatureRepository::class);
    }
}