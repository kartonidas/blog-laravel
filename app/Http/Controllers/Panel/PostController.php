<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Libraries\Client;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $page = intval($request->input("page", 1)) >= 1 ? intval($request->input("page", 1)) : 1;
        $data = Client::call("posts", ["page" => $page], "get");
        
        $data['can_edit'] = $request->session()->get("api_token", false);
        
        return view("panel.post.list", $data);
    }
    
    public function show(Request $request, $id)
    {
        $response = Client::call('posts/' . $id, [], 'get');
        if($response['status_code'] != '200')
            return redirect()->back()->withInput()->withErrors($response['data']['message']);
        
        return view("panel.post.show", $response);
    }
    
    public function create(Request $request)
    {
        return view("panel.post.create");
    }
    
    public function store(Request $request)
    {
        $images = [];
        for($i = 1; $i <= 3; $i++)
        {
            if($request->file('file_' . $i))
            {
                $images[] =
                [
                    'name' => 'images[]',
                    'tmp_name' => $request->file('file_' . $i)->getPathName(),
                    'file_name' => $request->file('file_' . $i)->getClientOriginalName(),
                ];
            }
        }
        
        $data = [
            'title' => $request->input('title', ''),
            'content' => $request->input('content', ''),
        ];
        $response = Client::call('posts', $data, 'post', true, $images);
        if($response['status_code'] != '200')
            return redirect()->back()->withInput()->withErrors($response['data']['message']);
        
        $request->session()->flash('status', 'Post has been created!');
        return redirect()->route('post.edit', $response['data']['id']);
    }
    
    public function edit(Request $request, $id)
    {
        $response = Client::call('posts/' . $id, [], 'get');
        if($response['status_code'] != '200')
            return redirect()->back()->withInput()->withErrors($response['data']['message']);
        
        return view("panel.post.update", $response);
    }
    
    public function update(Request $request, $id)
    {
        $images = [];
        for($i = 1; $i <= 3; $i++)
        {
            if($request->file('file_' . $i))
            {
                $images[] =
                [
                    'name' => 'images[]',
                    'tmp_name' => $request->file('file_' . $i)->getPathName(),
                    'file_name' => $request->file('file_' . $i)->getClientOriginalName(),
                ];
            }
        }
        
        $data = [
            'title' => $request->input('title', ''),
            'content' => $request->input('content', ''),
        ];
        $response = Client::call('posts/' . $id, $data, 'post', true, $images);
        if($response['status_code'] != '200')
            return redirect()->back()->withInput()->withErrors($response['data']['message']);
        
        $request->session()->flash('status', 'Post has been updated!');
        return redirect()->route('post.edit', $id);
    }
    
    public function delete(Request $request, $id)
    {
        $response = Client::call('posts/' . $id, [], 'delete', true);
        if($response['status_code'] != '200')
            return redirect()->back()->withInput()->withErrors($response['data']['message']);
        
        $request->session()->flash('status', 'Post has been deleted!');
        return redirect()->route('index');
    }
    
    public function deletePhoto(Request $request, $id, $pid)
    {
        $response = Client::call('posts/' . $id . '/photo/' . $pid, [], 'delete', true);
        if($response['status_code'] != '200')
            return redirect()->back()->withInput()->withErrors($response['data']['message']);
        
        $request->session()->flash('status', 'Photo has been deleted!');
        return redirect()->back();
    }
}