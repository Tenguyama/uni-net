<?php

namespace App\Services;

use App\Http\Requests\PostLike\PostLikeRequest;
use App\Repositories\PostLikeRepository;

class PostLikeService
{
    protected static $instance;

    private readonly PostLikeRepository $repository;

    protected function __construct(){
        $this->repository = PostLikeRepository::getInstance();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function create(PostLikeRequest $request){
        return $this->repository->save($request);
    }

    public function update(PostLikeRequest $request){
        return $this->repository->save($request);
    }
    public function delete(PostLikeRequest $request){
        return $this->repository->delete($request);
    }
}
