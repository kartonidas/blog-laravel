<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\PostImage;

class PostObserver
{
    function deleted(Post $post): void
    {
        $images = $post->images;
        if(!$images->isEmpty())
        {
            foreach($images as $image)
                $image->delete();
        }
    }
}
