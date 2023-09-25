<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\Hash;
use App\Models\Post;
use App\Models\User;

trait TestTrait
{
    private static $accounts = [
        'admin@testing.com' => ['John Doe', 'Password123@', 'admin'],
        'editor@testing.com' => ['John Doe', 'Password123@', 'editor'],
        'user@testing.com' => ['John Doe', 'Password123@', 'user'],
    ];
    
    public function getAccounts()
    {
        return static::$accounts;
    }
    
    public function createUserAccounts()
    {
        foreach(static::$accounts as $email => $account)
        {
            $user = new User;
            $user->email = $email;
            $user->name = $account[0];
            $user->password = Hash::make($account[1]);
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->user_role = $account[2];
            $user->save();
        }
        return true;
    }
    
    public function getLoginToken($email)
    {
        $account = static::$accounts[$email];
        
        $data = [
            'email' => $email,
            'password' => $account[1],
        ];
        $response = $this->postJson('/api/v1/login', $data);
        $return = json_decode($response->getContent());
        return $return->token;
    }
    
    public function getPostExampleData()
    {
        return [
            'title' => 'Example post',
            'content' => 'Lorem ipsum dolor sit amet...',
        ];
    }
    
    public function createPost()
    {
        $post = new Post;
        $post->title = $this->getPostExampleData()['title'];
        $post->content = $this->getPostExampleData()['content'];
        $post->user_id = 0;
        $post->save();
        
        return $post->id;
    }
    
    public function getUserExampleData($role = 'user')
    {
        return [
            'email' => 'example@testing.com',
            'name' => 'John Doe',
            'password' => 'Password123@',
            'user_role' => $role,
        ];
    }
    
    public function createUser($role = 'user')
    {
        $data = $this->getUserExampleData($role);
        
        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->user_role = $data['user_role'];
        $user->save();
        
        return $user->id;
    }
}