<?php

namespace App\Interfaces;

interface UserRepositoryInterface 
{
    public function register($name, $email, $password);
    public function activate($email, $token);
    public function findByEmail($email);
    public function getUsers($page = 1, $size = 10);
    public function getUserById($userId);
    public function createUser(array $userData);
    public function updateUser($userId, array $userData);
    public function deleteUser($userId);
    public function total();
    public function forgotenPassword($email);
    public function tokenResetPassword($data);
}