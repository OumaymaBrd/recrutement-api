<?php

namespace Tests\Feature;

use App\Models\Annonce;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnonceTest extends TestCase
{
    use RefreshDatabase;

    public function test_recruteur_can_create_annonce()
    {
        $recruteur = User::factory()->create([
            'role' => 'recruteur',
        ]);

        $token = $recruteur->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/annonces', [
            'titre' => 'Développeur Laravel',
            'description' => 'Nous recherchons un développeur Laravel expérimenté',
            'localisation' => 'Paris',
            'type_contrat' => 'CDI',
            'salaire' => 45000,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'id',
                     'titre',
                     'description',
                     'localisation',
                     'type_contrat',
                     'salaire',
                     'user_id',
                 ]);
    }

    public function test_candidat_cannot_create_annonce()
    {
        $candidat = User::factory()->create([
            'role' => 'candidat',
        ]);

        $token = $candidat->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/annonces', [
            'titre' => 'Développeur Laravel',
            'description' => 'Nous recherchons un développeur Laravel expérimenté',
            'localisation' => 'Paris',
            'type_contrat' => 'CDI',
            'salaire' => 45000,
        ]);

        $response->assertStatus(403);
    }

    public function test_can_get_all_annonces()
    {
        Annonce::factory()->count(5)->create();

        $response = $this->getJson('/api/annonces');

        $response->assertStatus(200)
                 ->assertJsonCount(5);
    }
}