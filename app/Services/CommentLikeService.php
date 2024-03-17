<?php

namespace App\Services;

use App\Http\Requests\CommentLike\CommentLikeRequest;
use App\Repositories\CommentLikeRepository;

class CommentLikeService
{
    protected static $instance;

    private readonly CommentLikeRepository $repository;

    protected function __construct(){
        $this->repository = CommentLikeRepository::getInstance();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function create(CommentLikeRequest $request){
        return $this->repository->save($request);
    }

    public function update(CommentLikeRequest $request){
        return $this->repository->save($request);
    }
    public function delete(CommentLikeRequest $request){
        return $this->repository->delete($request);
    }
}
