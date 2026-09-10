<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1 : Un utilisateur peut se connecter avec des identifiants valides.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'tech@dealtoo.com',
            'password' => Hash::make('password123'),
            'role' => UserRole::ADMIN,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'tech@dealtoo.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'access_token',
                'token_type',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'organization_id',
                ],
            ]);

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
        ]);
    }

    /**
     * Test 2 : Rejet en cas de mot de passe erroné (HTTP 422).
     */
    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'comptable@dealtoo.com',
            'password' => Hash::make('secret_password'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'mauvais_mot_de_passe',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test 3 : Rejet si l'email ou le mot de passe est manquant (FormRequest HTTP 422).
     */
    public function test_login_requires_email_and_password(): void
    {
        $response = $this->postJson('/api/auth/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    /**
     * Test 4 : Un utilisateur authentifié peut consulter son profil (/api/auth/me).
     */
    public function test_authenticated_user_can_access_me_profile(): void
    {
        $organization = Organization::factory()->create(['name' => 'Dealtoo Entreprise']);
        $user = User::factory()->create([
            'organization_id' => $organization->id,
            'role' => UserRole::ACCOUNTANT,
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/auth/me');

        $response->assertStatus(200)
            ->assertJsonPath('user.id', $user->id)
            ->assertJsonPath('user.email', $user->email)
            ->assertJsonPath('user.organization.name', 'Dealtoo Entreprise');
    }

    /**
     * Test 5 : Accès interdit sans jeton d'authentification (HTTP 401).
     */
    public function test_unauthenticated_user_cannot_access_me_profile(): void
    {
        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(401);
    }

    /**
     * Test 6 : Déconnexion et révocation du jeton (/api/auth/logout).
     */
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('logout-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Déconnexion réussie.']);

        // Vérifier que le jeton a bien été supprimé
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);

        // Réinitialiser le cache de garde en mémoire pour simuler une nouvelle requête HTTP isolée
        $this->app['auth']->forgetGuards();

        // Vérifier qu'une tentative suivante avec ce même jeton échoue
        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/auth/me')
            ->assertStatus(401);
    }

    /**
     * Test 7 : Le Rate Limiter bloque les tentatives excessives (anti-brute-force HTTP 429).
     */
    public function test_login_is_rate_limited_after_five_failed_attempts(): void
    {
        $user = User::factory()->create([
            'email' => 'cible@dealtoo.com',
            'password' => Hash::make('motdepassevalide'),
        ]);

        // 5 tentatives consécutives échouées autorisées
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/auth/login', [
                'email' => $user->email,
                'password' => 'mauvais_mdp',
            ])->assertStatus(422);
        }

        // La 6ème tentative doit être immédiatement bloquée en HTTP 429
        $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'mauvais_mdp',
        ])->assertStatus(429);
    }
}
