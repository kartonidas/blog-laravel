<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Libraries\Client;

class UserController extends Controller
{
    public function login(Request $request)
    {
        return view('panel.user.login');
    }
    
    public function loginPost(Request $request)
    {
        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
        $response = Client::call('login', $data, 'post');
        
        if($response['status_code'] != '200')
            return redirect()->back()->withInput()->withErrors($response['data']['message']);
       
        $request->session()->put('api_token', $response['data']['token']);
        
        return redirect()->route('index');
    }
    
    public function logout(Request $request)
    {
        $request->session()->forget('api_token');
        return redirect()->route('index');
    }
    
    public function register(Request $request)
    {
        return view('panel.user.register');
    }
    
    public function registerPost(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
        $response = Client::call('register', $data, 'post');
        
        if($response['status_code'] != '200')
            return redirect()->back()->withInput()->withErrors($response['data']['message']);
        
        return redirect()->route('user.register.ok');
    }
    
    public function registerOk(Request $request)
    {
        return view('panel.user.register_ok');
    }
    
    public function activate(Request $request)
    {
        $data = [
            'token' => $request->input('token', ''),
            'email' => $request->input('email', ''),
        ];
        $response = Client::call('activate', $data, 'post');
        return view('panel.user.activate', $response);
    }
    
    public function forgotPassword(Request $request)
    {
        return view('panel.user.forgot_password');
    }
    
    public function forgotPasswordPost(Request $request)
    {
        $data = [
            'email' => $request->input('email', ''),
        ];
        $response = Client::call('forgot-password', $data, 'post');
        if($response['status_code'] != '200')
            return redirect()->back()->withInput()->withErrors($response['data']['message']);
        
        return redirect()->route('user.forgot_password.ok');
    }
    
    public function forgotPasswordOk(Request $request)
    {
        return view('panel.user.forgot_password_ok');
    }
    
    public function resetPassword(Request $request)
    {
        return view('panel.user.reset_password');
    }
    
    public function resetPasswordPost(Request $request)
    {
        $data = [
            'email' => $request->input('email', ''),
            'password' => $request->input('password', ''),
            'token' => $request->input('token', ''),
        ];
        $response = Client::call('reset-password', $data, 'post');
        if($response['status_code'] != '200')
            return redirect()->back()->withInput()->withErrors($response['data']['message']);
        
        return redirect()->route('user.reset_password.ok');
    }
    
    public function resetPasswordOk(Request $request)
    {
        return view('panel.user.reset_password_ok');
    }
    
    public function index(Request $request)
    {
        $page = intval($request->input("page", 1)) >= 1 ? intval($request->input("page", 1)) : 1;
        $data = Client::call("users", ["page" => $page], "get", true);
        return view("panel.users.list", $data);
    }
    
    public function create(Request $request)
    {
        return view("panel.users.create");
    }
    
    public function store(Request $request)
    {
        $data = [
            'name' => $request->input('name', ''),
            'email' => $request->input('email', ''),
            'user_role' => $request->input('user_role', ''),
            'password' => $request->input('password', ''),
        ];
        $response = Client::call('users', $data, 'post', true);
        if($response['status_code'] != '200')
            return redirect()->back()->withInput()->withErrors($response['data']['message']);
        
        return redirect()->route('user.edit', $response['data']['id']);
    }
    
    public function edit(Request $request, $id)
    {
        $response = Client::call('users/' . $id, [], 'get', true);
        if($response['status_code'] != '200')
            return redirect()->back()->withInput()->withErrors($response['data']['message']);
        
        return view("panel.users.update", $response);
    }
    
    public function update(Request $request, $id)
    {
        $data = [
            'name' => $request->input('name', ''),
            'email' => $request->input('email', ''),
            'user_role' => $request->input('user_role', ''),
        ];
        
        if($request->has('change_password'))
            $data['password'] = $request->input('password', '') ?? false;
        
        $response = Client::call('users/' . $id, $data, 'post', true);
        if($response['status_code'] != '200')
            return redirect()->back()->withInput()->withErrors($response['data']['message']);
        
        return redirect()->route('user.edit', $id);
    }
    
    public function delete(Request $request, $id)
    {
        $response = Client::call('users/' . $id, [], 'delete', true);
        if($response['status_code'] != '200')
            return redirect()->back()->withInput()->withErrors($response['data']['message']);
        
        return redirect()->route('users');
    }
}