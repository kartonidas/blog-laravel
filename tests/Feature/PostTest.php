<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\Traits\TestTrait;

class PostTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    /**
     * Lista postów (pusta lista)
     */
    public function test_posts_ok(): void
    {
        $response = $this->getJson('/api/v1/posts');
        $response->assertStatus(200);
    }
    
    // Lista postów
    public function test_posts_ok2(): void
    {
        $this->createPost();
        $this->createPost();
        $this->createPost();
        
        $response = $this->getJson('/api/v1/posts');
        $response->assertStatus(200);
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json
                ->where('total_rows', 3)
                ->where('total_pages', 1)
                ->where('current_page', 1)
                ->etc()
        );
    }
    
    /**
     * Lista postów - nieprawidłowe parametry
     */
    public function test_posts_invalid_params(): void
    {
        $response = $this->getJson('/api/v1/posts?size=-1');
        $response->assertStatus(422);
        
        $response = $this->getJson('/api/v1/posts?size=XXX');
        $response->assertStatus(422);
        
        $response = $this->getJson('/api/v1/posts?page=-1');
        $response->assertStatus(422);
        
        $response = $this->getJson('/api/v1/posts?page=XXX');
        $response->assertStatus(422);
    }
    
    /**
     * Utworzenie nowego wpisu - użytkownik niezalogowany
     */
    public function test_create_post_unauthorized(): void
    {
        $response = $this->postJson('/api/v1/posts', $this->getPostExampleData());
        $response->assertStatus(401);
    }

    /**
     * Utworzenie nowego wpisu - administrator
     */
    public function test_create_post_admin_ok(): void
    {
        $this->createUserAccounts();
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->postJson('/api/v1/posts', $this->getPostExampleData());
        $response->assertStatus(200);
        
        $this->assertDatabaseCount('posts', 1);
        $this->assertDatabaseHas('posts', [
            'title' => $this->getPostExampleData()['title'],
            'content' => $this->getPostExampleData()['content'],
        ]);
    }
    
    /**
     * Utworzenie nowego wpisu - redaktor
     */
    public function test_create_post_editor_ok(): void
    {
        $this->createUserAccounts();
        $response = $this->withToken($this->getLoginToken('editor@testing.com'))->postJson('/api/v1/posts', $this->getPostExampleData());
        $response->assertStatus(200);
        
        $this->assertDatabaseCount('posts', 1);
        $this->assertDatabaseHas('posts', [
            'title' => $this->getPostExampleData()['title'],
            'content' => $this->getPostExampleData()['content'],
        ]);
    }
    
    /**
     * Utworzenie nowego wpisu - nieprawidłowe parametry
     */
    public function test_create_post_invalid_params(): void
    {
        $this->createUserAccounts();
        
        // puste dane
        $data = [];
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->postJson('/api/v1/posts', $data);
        $response->assertStatus(422);
        
        // za krótki tytuł
        $data = ['title' => '1', 'content' => 'Lorem'];
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->postJson('/api/v1/posts', $data);
        $response->assertStatus(422);
        
        // brak zawartości
        $data = ['title' => 'Example title'];
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->postJson('/api/v1/posts', $data);
        $response->assertStatus(422);
        
        // ok
        $data = ['title' => 'Example title', 'content' => 'Lorem'];
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->postJson('/api/v1/posts', $data);
        $response->assertStatus(200);
        
        $this->assertDatabaseCount('posts', 1);
    }
    
    /**
     * Aktualizacja wpisu - administrator
     */
    public function test_update_post_admin_ok(): void
    {
        $this->createUserAccounts();
        $postId = $this->createPost();
        
        $data = [
            'title' => 'Updated title',
        ];
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->postJson('/api/v1/posts/' . $postId, $data);
        $this->assertDatabaseHas('posts', [
            'title' => 'Updated title',
        ]);
        $response->assertStatus(200);
        
        $data = [
            'content' => 'Updated content',
        ];
        $response = $this->withToken($this->getLoginToken('editor@testing.com'))->postJson('/api/v1/posts/' . $postId, $data);
        $this->assertDatabaseHas('posts', [
            'title' => 'Updated title',
            'content' => 'Updated content',
        ]);
        $response->assertStatus(200);
        
        $data = [
            'title' => 'Updated title #1',
            'content' => 'Updated content #1',
        ];
        $response = $this->withToken($this->getLoginToken('editor@testing.com'))->postJson('/api/v1/posts/' . $postId, $data);
        $this->assertDatabaseHas('posts', [
            'title' => 'Updated title #1',
            'content' => 'Updated content #1',
        ]);
        $response->assertStatus(200);
    }
    
    /**
     * Aktualizacja wpisu - edytor
     */
    public function test_update_post_editor_ok(): void
    {
        $this->createUserAccounts();
        $postId = $this->createPost();
        
        $data = [
            'title' => 'Updated title',
            'content' => 'Updated content',
        ];
        $response = $this->withToken($this->getLoginToken('editor@testing.com'))->postJson('/api/v1/posts/' . $postId, $data);
        $this->assertDatabaseHas('posts', [
            'title' => 'Updated title',
            'content' => 'Updated content',
        ]);
        $response->assertStatus(200);
    }
    
    /**
     * Usunięcie wpisu - administrator
     */
    public function test_delete_post_admin_ok(): void
    {
        $this->createUserAccounts();
        $postId = $this->createPost();
        
        $response = $this->withToken($this->getLoginToken('admin@testing.com'))->deleteJson('/api/v1/posts/' . $postId);
        $response->assertStatus(200);
        $this->assertDatabaseCount('posts', 0);
    }
    
    /**
     * Usunięcie wpisu - redaktor
     */
    public function test_delete_post_editor_ok(): void
    {
        $this->createUserAccounts();
        $postId = $this->createPost();
        
        $response = $this->withToken($this->getLoginToken('editor@testing.com'))->deleteJson('/api/v1/posts/' . $postId);
        $response->assertStatus(200);
        $this->assertDatabaseCount('posts', 0);
    }
}
