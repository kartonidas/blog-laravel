<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;

use App\Models\User;
use Tests\TestCase;
use Tests\Traits\TestTrait;

class UserTest extends TestCase
{
    use RefreshDatabase, TestTrait;
    
    /**
     * Rejestracja
     */
    public function test_register_ok(): void
    {
        $data = [
            'name' => $this->getUserExampleData()['name'],
            'email' => $this->getUserExampleData()['email'],
            'password' => $this->getUserExampleData()['password'],
        ];
        $response = $this->postJson('/api/v1/register', $data);
        $response->assertStatus(200);
        
        $this->assertDatabaseHas('users', [
            'email' => $this->getUserExampleData()['email'],
            'user_role' => 'user',
        ]);
    }
    
    /**
     * Rejestracja (nieprawidłowe parametry)
     */
    public function test_register_invalid_params(): void
    {
        $data = [];
        $response = $this->postJson('/api/v1/register', $data);
        $response->assertStatus(422);
        
        $data = [
            'email' => 'xxx',
        ];
        $response = $this->postJson('/api/v1/register', $data);
        $response->assertStatus(422);
        
        $data = [
            'email' => $this->getUserExampleData()['email'],
            'name' => '',
        ];
        $response = $this->postJson('/api/v1/register', $data);
        $response->assertStatus(422);
        
        $data = [
            'email' => $this->getUserExampleData()['email'],
            'name' => $this->getUserExampleData()['name'],
            'password' => ''
        ];
        $response = $this->postJson('/api/v1/register', $data);
        $response->assertStatus(422);
        
        $data = [
            'email' => $this->getUserExampleData()['email'],
            'name' => $this->getUserExampleData()['name'],
            'password' => 'xxx'
        ];
        $response = $this->postJson('/api/v1/register', $data);
        $response->assertStatus(422);
        
        $data = [
            'email' => $this->getUserExampleData()['email'],
            'name' => $this->getUserExampleData()['name'],
            'password' => $this->getUserExampleData()['password']
        ];
        $response = $this->postJson('/api/v1/register', $data);
        $response->assertStatus(200);
    }
    
    /**
     * Aktywacja konta
     */
    public function test_activation_ok(): void
    {
        $data = [
            'name' => $this->getUserExampleData()['name'],
            'email' => $this->getUserExampleData()['email'],
            'password' => $this->getUserExampleData()['password'],
        ];
        $response = $this->postJson('/api/v1/register', $data);
        $response->assertStatus(200);
        
        $user = User::where('email', $this->getUserExampleData()['email'])->first();
        
        $data = [
            'email' => $this->getUserExampleData()['email'],
            'token' => $user->registration_token,
        ];
        $response = $this->postJson('/api/v1/activate', $data);
        $response->assertStatus(200);
        
        $user = User::where('email', $this->getUserExampleData()['email'])->first();
        $this->assertNotNull($user->email_verified_at);
    }
    
    /**
     * Aktywacja konta (nieprawłowy token)
     */
    public function test_activation_invalid_params(): void
    {
        $data = [
            'name' => $this->getUserExampleData()['name'],
            'email' => $this->getUserExampleData()['email'],
            'password' => $this->getUserExampleData()['password'],
        ];
        $response = $this->postJson('/api/v1/register', $data);
        $response->assertStatus(200);
        
        $user = User::where('email', $this->getUserExampleData()['email'])->first();
        
        $data = [
            'email' => $this->getUserExampleData()['email'],
            'token' => 'xxxx',
        ];
        $response = $this->postJson('/api/v1/activate', $data);
        $response->assertStatus(422);
        
        $user = User::where('email', $this->getUserExampleData()['email'])->first();
        $this->assertNull($user->email_verified_at);
        
        $data = [
            'email' => 'xxx@wp.pl',
            'token' => $user->registration_token,
        ];
        $response = $this->postJson('/api/v1/activate', $data);
        $response->assertStatus(404);
        
        $user = User::where('email', $this->getUserExampleData()['email'])->first();
        $this->assertNull($user->email_verified_at);
    }
    
    /**
     * Rejestracja, aktywacja, logowanie
     */
    public function test_register_activation_login_ok(): void
    {
        $data = [
            'name' => $this->getUserExampleData()['name'],
            'email' => $this->getUserExampleData()['email'],
            'password' => $this->getUserExampleData()['password'],
        ];
        $response = $this->postJson('/api/v1/register', $data);
        $response->assertStatus(200);
        
        $this->assertDatabaseHas('users', [
            'email' => $this->getUserExampleData()['email'],
            'user_role' => 'user',
        ]);
        
        $user = User::where('email', $this->getUserExampleData()['email'])->first();
        $user->user_role = 'editor';
        $user->save();
        
        $data = [
            'email' => $this->getUserExampleData()['email'],
            'token' => $user->registration_token,
        ];
        $response = $this->postJson('/api/v1/activate', $data);
        $response->assertStatus(200);
        
        $data = [
            'email' => $this->getUserExampleData()['email'],
            'password' => $this->getUserExampleData()['password'],
        ];
        $response = $this->postJson('/api/v1/login', $data);
        $response->assertStatus(200);
    }
    
    /**
     * Logowanie (admin)
     */
    public function test_login_admin_ok(): void
    {
        $this->createUserAccounts();
        
        $data = [
            'email' => 'admin@testing.com',
            'password' => $this->getAccounts()['admin@testing.com'][1],
        ];
        $response = $this->postJson('/api/v1/login', $data);
        $response->assertStatus(200);
    }
    
    /**
     * Logowanie (redaktor)
     */
    public function test_login_editor_ok(): void
    {
        $this->createUserAccounts();
        
        $data = [
            'email' => 'editor@testing.com',
            'password' => $this->getAccounts()['editor@testing.com'][1],
        ];
        $response = $this->postJson('/api/v1/login', $data);
        $response->assertStatus(200);
    }
    
    /**
     * Logowanie (użytkownik)
     */
    public function test_login_user_error(): void
    {
        $this->createUserAccounts();
        
        $data = [
            'email' => 'user@testing.com',
            'password' => $this->getAccounts()['user@testing.com'][1],
        ];
        $response = $this->postJson('/api/v1/login', $data);
        $response->assertStatus(422);
    }
    
    /**
     * Logowanie (użytkownik nie aktywowany)
     */
    public function test_login_not_verified(): void
    {
        $this->createUserAccounts();
        $this->createUser('admin');
        
        $data = [
            'email' => $this->getUserExampleData()['email'],
            'password' => $this->getUserExampleData()['password'],
        ];
        $response = $this->postJson('/api/v1/login', $data);
        $response->assertStatus(422);
    }
    
    /**
     * Lista użytkowników
     */
    public function test_users_ok(): void
    {
        $this->createUserAccounts();
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->getJson('/api/v1/users');
        $response->assertStatus(200);
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json
                ->where('total_rows', 4)
                ->where('total_pages', 1)
                ->where('current_page', 1)
                ->etc()
        );
    }
    
    /**
     * Lista użytkowników (użytkownik nie zalogowany)
     */
    public function test_users_unauthorized(): void
    {
        $this->createUserAccounts();
        $response = $this->getJson('/api/v1/users');
        $response->assertStatus(401);
    }
    
    /**
     * Lista użytkowników (redaktor)
     */
    public function test_users_editor_unauthorized(): void
    {
        $this->createUserAccounts();
        $response = $this->withToken($this->getLoginToken('editor@testing.com'))->getJson('/api/v1/users');
        $response->assertStatus(403);
    }
    
    /**
     * Lista użytkowników - nieprawidłowe parametry
     */
    public function test_users_invalid_params(): void
    {
        $this->createUserAccounts();
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->getJson('/api/v1/users?size=-1');
        $response->assertStatus(422);
        
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->getJson('/api/v1/users?size=XXX');
        $response->assertStatus(422);
        
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->getJson('/api/v1/users?page=-1');
        $response->assertStatus(422);
        
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->getJson('/api/v1/users?page=XXX');
        $response->assertStatus(422);
    }
    
    /**
     * Utworzenie nowego użytkownika - użytkownik niezalogowany
     */
    public function test_create_user_unauthorized(): void
    {
        $response = $this->postJson('/api/v1/users', $this->getUserExampleData());
        $response->assertStatus(401);
    }
    
    /**
     * Utworzenie nowego wpisu - administrator
     */
    public function test_create_user_admin_ok(): void
    {
        $this->createUserAccounts();
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->postJson('/api/v1/users', $this->getUserExampleData());
        $response->assertStatus(200);
        
        $this->assertDatabaseCount('users', 5);
    }
    
    /**
     * Utworzenie nowego wpisu - redaktor
     */
    public function test_create_user_editor_unauthorized_error(): void
    {
        $this->createUserAccounts();
        $response = $this->withToken($this->getLoginToken('editor@testing.com'))->postJson('/api/v1/users', $this->getUserExampleData());
        $response->assertStatus(403);
        
        $this->assertDatabaseCount('users', 4);
    }
    
    /**
     * Utworzenie nowego wpisu - nieprawidłowe parametry
     */
    public function test_create_user_invalid_params(): void
    {
        $this->createUserAccounts();
        
        $data = [];
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->postJson('/api/v1/users', $data);
        $response->assertStatus(422);
        
        // nieprawidłowy adres e-mail
        $data = ['email' => 'invalid_email'];
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->postJson('/api/v1/users', $data);
        $response->assertStatus(422);
        
        // za krótka nazwa
        $data = ['email' => 'example@example.com', 'name' => 'n'];
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->postJson('/api/v1/users', $data);
        $response->assertStatus(422);
        
        // za długa nazwa
        $data = ['email' => 'example@example.com', 'name' => 'invalid name invalid name invalid name invalid name'];
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->postJson('/api/v1/users', $data);
        $response->assertStatus(422);
        
        // za słabe hasło
        $data = ['email' => 'example@example.com', 'name' => 'John Doe', 'password' => '123'];
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->postJson('/api/v1/users', $data);
        $response->assertStatus(422);
        
        // nieprawidłowe uprawnienia
        $data = ['email' => 'example@example.com', 'name' => 'John Doe', 'password' => '123', 'user_role' => 'edditor'];
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->postJson('/api/v1/users', $data);
        $response->assertStatus(422);
        
        // ok
        $data = ['email' => 'example@example.com', 'name' => 'John Doe', 'password' => 'Password123@', 'user_role' => 'user'];
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->postJson('/api/v1/users', $data);
        $response->assertStatus(200);
    }
    
    /**
     * Aktualizacja wpisu - administrator
     */
    public function test_update_admin_ok(): void
    {
        $this->createUserAccounts();
        $userId = $this->createUser();
        
        $data = ['name' => 'John Doe'];
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->postJson('/api/v1/users/' . $userId, $data);
        $response->assertStatus(200);
        
        $this->assertDatabaseHas('users', [
            'email' => $this->getUserExampleData()['email'],
            'name' => 'John Doe',
        ]);
    }
    
    /**
     * Aktualizacja wpisu - edytor
     */
    public function test_update_user_editor_unauthorized_error(): void
    {
        $this->createUserAccounts();
        $userId = $this->createUser();
        
        $data = ['name' => 'John Doe'];
        $response = $this->withToken($this->getLoginToken('editor@testing.com'))->postJson('/api/v1/users/' . $userId, $data);
        $response->assertStatus(403);
    }
    
    /**
     * Usunięcie wpisu - administrator
     */
    public function test_delete_admin_ok(): void
    {
        $this->createUserAccounts();
        $userId = $this->createUser();
        
        $data = ['name' => 'John Doe'];
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->deleteJson('/api/v1/users/' . $userId, $data);
        $response->assertStatus(200);
    }
    
    /**
     * Usunięcie wpisu - edytor
     */
    public function test_delete_editor_error(): void
    {
        $this->createUserAccounts();
        $userId = $this->createUser();
        
        $data = ['name' => 'John Doe'];
        $response = $this->withToken($this->getLoginToken('editor@testing.com'))->deleteJson('/api/v1/users/' . $userId, $data);
        $response->assertStatus(403);
    }
    
    /**
     * Szczegóły wpisu - administrator
     */
    public function test_details_admin_ok(): void
    {
        $this->createUserAccounts();
        $userId = $this->createUser();
        
        $data = ['name' => 'John Doe'];
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->getJson('/api/v1/users/' . $userId, $data);
        $response->assertStatus(200);
    }
    
    /**
     * Szczegóły wpisu - administrator
     */
    public function test_details_editor_error(): void
    {
        $this->createUserAccounts();
        $userId = $this->createUser();
        
        $data = ['name' => 'John Doe'];
        $response = $this->withToken($this->getLoginToken('editor@testing.com'))->getJson('/api/v1/users/' . $userId, $data);
        $response->assertStatus(403);
    }
}
