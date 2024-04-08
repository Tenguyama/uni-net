<?php

namespace App\Services;

use App\Http\Requests\Post\PostRequest;
use App\Models\Post;
use App\Repositories\PostRepository;

class PostService
{
    protected static $instance;

    private readonly PostRepository $repository;

    protected function __construct(){
        $this->repository = PostRepository::getInstance();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getAllRecommendationPost(){
        return $this->repository->getAllRecommendationPost();
    }
    public function getAllPostByTrackable(){
        return $this->repository->getAllPostByTrackable();
    }
    public function getPostById(Post $post){
        return $this->repository->getPostById($post);
    }

    public function create(PostRequest $request){
        return $this->repository->save($request, null);
    }

    public function update(PostRequest $request, Post $post){
        return $this->repository->save($request, $post);
    }

    public function delete(Post $post){
        return $this->repository->delete($post);
    }
}
