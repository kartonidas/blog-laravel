<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\UserActivateRequest;
use App\Http\Requests\UserDestroyRequest;
use App\Http\Requests\UserForgotPasswordRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserResetPasswordRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserShowRequest;
use App\Http\Requests\UserStoreRequest;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserController extends Controller
{
    private userRepositoryInterface $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepositoryInterface = $userRepository;
    }
    
    /**
     * Rejestracja.
    */
    public function register(UserRegisterRequest $request)
    {
        $requestData = $request->safe()->only(['name', 'email', 'password']);
        $this->userRepositoryInterface->register($requestData['name'], $requestData['email'], $requestData['password']);
        
        return [
            /** @var boolean $ok Status operacji. */
            'ok' => true
        ];
    }
    
    /**
     * Aktywacja.
    */
    public function activate(UserActivateRequest $request)
    {
        $requestData = $request->safe()->only(['token', 'email']);
        $this->userRepositoryInterface->activate($requestData['email'], $requestData['token']);
        
        return [
            /** @var boolean $ok Status operacji. */
            'ok' => true
        ];
    }
    
    /**
     * Logowanie.
    */
    public function login(UserLoginRequest $request)
    {
        $requestData = $request->safe()->only(['email', 'password']);
        $user = $this->userRepositoryInterface->findByEmail($requestData['email']);
        
        if(!$user || !Hash::check($requestData['password'], $user->password) || !$user->email_verified_at || !in_array($user->user_role, [User::ROLE_EDITOR, User::ROLE_ADMIN]))
        {
            throw ValidationException::withMessages([
                "email" => [__("The provided credentials are incorrect.")],
            ]);
        }
        
        $token = $user->createToken('api');
        
        $out = [
            /** @var integer Identyfikator użytkownika. */
            "id" => $user->id,
            /** @var integer Token. */
            "token" => $token->plainTextToken,
            /** @var integer Nazwa użytkownika. */
            "name" => $user->name,
        ];
        return $out;
    }
    
    /**
     * Lista użytkowników.
    */
    public function index(UserRequest $request)
    {
        $requestData = $request->safe()->only(['page', 'size']);
        
        $page = $requestData['page'] ?? 1;
        $size = $requestData['size'] ?? 10;
        $users = $this->userRepositoryInterface->getUsers($page, $size);
        
        $total = $this->userRepositoryInterface->total();
        $out = [
            /** @var integer Łączna ilość wszystkich rekordów. */
            'total_rows' => $total,
            /** @var integer Łączna ilość podstron. */
            'total_pages' => ceil($total / $size),
            /** @var integer Aktualna podstrona. */
            'current_page' => $page,
            /** @var boolean Czy jest następna podstrona? */
            'has_more' => ceil($total / $size) > $page,
            /** @var array{array{id: int, name: string, email: string, user_role: string, created_at: string}} Lista użytkowników */
            'data' => $users,
        ];
        return $out;
    }
    
    /**
     * Szczegóły użytkownika.
     *
     * @param integer $id - Identyfikator użytkownika
     * @response array{id: int, name: string, email: string, user_role: string, created_at: string}
    */
    public function show(UserShowRequest $request, $id)
    {
        return $this->userRepositoryInterface->getUserById($id);
    }
    
    /**
     * Utworzenie użytkownika.
    */
    public function store(UserStoreRequest $request)
    {
        $userData = $request->safe()->only(['name', 'email', 'password', 'user_role']);
        $userData['email_verified_at'] = date('Y-m-d H:i:s');
        $user = $this->userRepositoryInterface->createUser($userData);
        
        return [
            /** @var boolean $id Identyfikator utworzonego użytkownika. */
            'id' => $user->id
        ];
    }
    
    /**
     * Aktualizacja użytkownika.
     *
     * @param integer $id - Identyfikator użytkownika
    */
    public function update(UserStoreRequest $request, $id)
    {
        $userData = $request->safe()->only(['name', 'email', 'password', 'user_role']);
        $this->userRepositoryInterface->updateUser($id, $userData);
        
        return [
            /** @var boolean $id Identyfikator użytkownika. */
            'id' => $id
        ];
    }
    
    /**
     * Usunięcie użytkownika.
     *
     * @param integer $id - Identyfikator użytkownika
    */
    public function destroy(UserDestroyRequest $request, $id)
    {
        $state = $this->userRepositoryInterface->deleteUser($id);
        
        return [
            /** @var boolean $ok Status operacji. */
            'ok' => $state
        ];
    }
    
    /**
     * Przypomnienie hasła.
    */
    public function forgotPassword(UserForgotPasswordRequest $request)
    {
        $userData = $request->safe()->only(['email']);
        $state = $this->userRepositoryInterface->forgotenPassword($userData['email']);
        
        return [
            /** @var boolean $ok Status operacji. */
            'ok' => $state
        ];
    }
    
    /**
     * Reset hasła.
    */
    public function resetPassword(UserResetPasswordRequest $request)
    {
        $requestData = $request->safe()->only(['token', 'email', 'password']);
        $state = $this->userRepositoryInterface->tokenResetPassword($requestData);
    
        return [
            /** @var boolean $ok Status operacji. */
            'ok' => $state
        ];
    }
}