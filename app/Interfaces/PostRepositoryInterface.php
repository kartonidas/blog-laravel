<?php

namespace App\Interfaces;

interface PostRepositoryInterface 
{
    public function getPosts($page = 1, $size = 10);
    public function getPostById($postId);
    public function createPost(array $postData);
    public function updatePost($postId, array $postData);
    public function deletePost($postId);
    public function total();
}