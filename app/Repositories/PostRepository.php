<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Exceptions\ObjectNotExists;
use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use App\Models\PostImage;

class PostRepository implements PostRepositoryInterface 
{
    public function getPosts($page = 1, $size = 10)
    {
        return Post::take($size)
            ->skip(($page - 1) * $size)
            ->orderBy('created_at', 'DESC')
            ->get();
    }
    
    public function getPostById($postId)
    {
        $post = Post::find($postId);
        if(!$post)
            throw new ObjectNotExists('Post does not exist');
        return $post;
    }
    
    public function createPost(array $postData)
    {
        $post = Post::create($postData);
        if(!empty($postData['images']))
            $this->storeImages($post, $postData['images']);
        
        return $post;
    }
    
    public function updatePost($postId, array $postData)
    {
        $post = $this->getPostById($postId);
        $post->update($postData);
        if(!empty($postData['images']))
            $this->storeImages($post, $postData['images']);
            
        return $post;
    }
    
    public function deletePost($postId)
    {
        return Post::destroy($postId);
    }
    
    public function total()
    {
        return Post::count();
    }
    
    private function storeImages(Post $post, $images)
    {
        foreach($images as $image)
        {
            $ext = $image->getClientOriginalExtension();
            $filename = bin2hex(openssl_random_pseudo_bytes(16)) . '.' . $ext;
            $path = $image->storeAs('images', $filename, 'upload');
            
            $imageData = [
                'post_id' => $post->id,
                'filename' => $filename,
                'orig_filename' => $image->getClientOriginalName(),
            ];
            PostImage::create($imageData);
        }
    }
}
