<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->create($data);
    }

    public function login(array $credentials)
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les informations d\'identification fournies sont incorrectes.'],
            ]);
        }

        return $user->createToken('auth_token')->plainTextToken;
    }

    public function logout($user)
    {
        return $user->tokens()->delete();
    }

    public function refreshToken($user)
    {
        $user->tokens()->delete();
        return $user->createToken('auth_token')->plainTextToken;
    }

    public function forgotPassword($email)
    {
        // Implémentation de la réinitialisation du mot de passe
        // Envoyer un email avec un lien de réinitialisation
    }

    public function resetPassword($token, $email, $password)
    {
        // Vérifier le token et réinitialiser le mot de passe
    }
}