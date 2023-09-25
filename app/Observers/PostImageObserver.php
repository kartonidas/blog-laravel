<?php

namespace App\Observers;

use App\Models\PostImage;

class PostImageObserver
{
    function deleted(PostImage $image): void
    {
        $file = storage_path('images/' . $image->filename);
        if(file_exists($file))
            unlink($file);
    }
}
