<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\PostDestroyRequest;
use App\Http\Requests\PostRequest;
use App\Http\Requests\PostStoreRequest;
use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;

class PostController extends Controller
{
    private postRepositoryInterface $postRepository;
    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepositoryInterface = $postRepository;
    }
    
    /**
     * Lista postów.
    */
    public function index(PostRequest $request)
    {
        $requestData = $request->safe()->only(['page', 'size']);
        
        $page = $requestData['page'] ?? 1;
        $size = $requestData['size'] ?? 10;
        $posts = $this->postRepositoryInterface->getPosts($page, $size);
        
        $total = $this->postRepositoryInterface->total();
        
        $out = [
            /** @var integer Łączna ilość wszystkich rekordów. */
            'total_rows' => $total,
            /** @var integer Łączna ilość podstron. */
            'total_pages' => ceil($total / $size),
            /** @var integer Aktualna podstrona. */
            'current_page' => $page,
            /** @var boolean Czy jest następna podstrona? */
            'has_more' => ceil($total / $size) > $page,
            /** @var array{array{id: int, user_id: int, title: string, content: string, created_at: string, updated_at: string}} Lista postów */
            'data' => $posts,
        ];
        return $out;
    }
    
    /**
     * Szczegóły postu.
     *
     * @param integer $id - Identyfikator postu
     * @response array{id: int, user_id: int, title: string, content: string, created_at: string, updated_at: string, images: array{array{id: int, post_id: int, filename: string, created_at: string, updated_at: string}}}
    */
    public function show(Request $request, $id)
    {
        $post = $this->postRepositoryInterface->getPostById($id);
        $post->images = $post->images;
        return $post;
    }
    
    /**
     * Nowy post.
    */
    public function store(PostStoreRequest $request)
    {
        $postData = $request->safe()->only(['title', 'content', 'images']);
        $postData['user_id'] = $request->user()->id;
        $post = $this->postRepositoryInterface->createPost($postData);
        
        return [
            /** @var integer $id Identyfikator utworzonego postu. */
            'id' => $post->id,
        ];
    }
    
    /**
     * Aktualizacja postu.
     *
     * @param integer $id - Identyfikator postu
    */
    public function update(PostStoreRequest $request, $id)
    {
        $postData = $request->safe()->only(['title', 'content', 'images']);
        $this->postRepositoryInterface->updatePost($id, $postData);
        
        return [
            /** @var integer $id Identyfikator postu. */
            'id' => $id
        ];
    }
    
    /**
     * Usunięcie postu.
     *
     * @param integer $id - Identyfikator postu
    */
    public function destroy(PostDestroyRequest $request, $id)
    {
        $state = $this->postRepositoryInterface->deletePost($id);
        
        return [
            /** @var boolean $ok Status operacji. */
            'ok' => $state
        ];
    }
    
    /**
     * Usunięcie zdjęcia.
     *
     * @param integer $id - Identyfikator postu
     * @param integer $pid - Identyfikator zdjęcia
    */
    public function destroyPhoto(PostDestroyRequest $request, $id, $pid)
    {
        $state = false;
        $post = $this->postRepositoryInterface->getPostById($id);
        $image = $post->images->find($pid);
        
        if($image)
            $state = $image->delete();
        
        return [
            /** @var boolean $ok Status operacji. */
            'ok' => $state
        ];
    }
}
