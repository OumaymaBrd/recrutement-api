<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Interfaces
use App\Repositories\UserRepositoryInterface;
use App\Repositories\AnnonceRepositoryInterface;
use App\Repositories\CandidatureRepositoryInterface;

// Implementations
use App\Repositories\UserRepository;
use App\Repositories\AnnonceRepository;
use App\Repositories\CandidatureRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AnnonceRepositoryInterface::class, AnnonceRepository::class);
        $this->app->bind(CandidatureRepositoryInterface::class, CandidatureRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}