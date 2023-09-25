<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

use App\Exceptions\Exception;
use App\Exceptions\ObjectNotExists;
use App\Interfaces\UserRepositoryInterface;
use App\Mail\RegisterMessageMail;
use App\Mail\ForgotPasswordMail;
use App\Models\User;

class UserRepository implements UserRepositoryInterface 
{
    public function register($name, $email, $password)
    {
        $token = Str::random(20);
        $userRegisterData = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'registration_token' => $token,
        ];
        $user = $this->createUser($userRegisterData);
        
        $url = route('user.activate', ['token' => $token, 'email' => $user->email]);
        Mail::to($user->email)->queue(new RegisterMessageMail($user, $token, $url));
    }
    
    public function activate($email, $token)
    {
        $user = $this->findByEmail($email);
        
        if($user->registration_token != $token)
            throw new Exception('Invalid token');
        
        if($user->registration_token)
        {
            $user->registration_token = null;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->save();
        }
        
        return true;
    }
    
    public function findByEmail($email)
    {
        $user = User::where("email", $email)->first();
        if(!$user)
            throw new ObjectNotExists('User does not exist');
        return $user;
    }
    
    public function getUsers($page = 1, $size = 10)
    {
        return User::fields()
            ->take($size)
            ->skip(($page - 1) * $size)
            ->orderBy('name', 'ASC')
            ->get();
    }
    
    public function getUserById($userId)
    {
        $user = User::fields()->find($userId);
        if(!$user)
            throw new ObjectNotExists('User does not exist');
        return $user;
    }
    
    public function createUser(array $userData)
    {
        $userData['password'] = Hash::make($userData['password']);
        return User::create($userData);
    }
    
    public function updateUser($userId, array $userData)
    {
        if(array_key_exists('password', $userData) && empty($userData['password']))
            unset($userData['password']);
            
        if(!empty($userData['password']))
            $userData['password'] = Hash::make($userData['password']);
            
        return User::whereId($userId)->update($userData);
    }
    
    public function deleteUser($userId)
    {
        return User::destroy($userId);
    }
    
    public function total()
    {
        return User::count();
    }
    
    public function forgotenPassword($email)
    {
        $user = $this->findByEmail($email);
        $token = Password::broker('users')->createToken($user);
        
        $url = route('user.reset_password', ['token' => $token, 'email' => $user->email]);
        Mail::to($user->email)->queue(new ForgotPasswordMail($user, $token, $url));
        
        return true;
    }
    
    public function tokenResetPassword($data)
    {
        $state = Password::broker('users')->reset($data, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });
        
        if($state == Password::broker('users')::PASSWORD_RESET)
            return true;
        
        return false;
    }
}
