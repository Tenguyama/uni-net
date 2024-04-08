<?php

namespace App\Services;

use App\Http\Requests\Comment\CommentRequest;
use App\Models\Comment;
use App\Repositories\CommentRepository;

class CommentService
{
    protected static $instance;

    private readonly CommentRepository $repository;

    protected function __construct(){
        $this->repository = CommentRepository::getInstance();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function create(CommentRequest $request){
        return $this->repository->save($request);
    }

    public function update(CommentRequest $request){
        return $this->repository->save($request);
    }

    public function delete(Comment $comment){
        return $this->repository->delete($comment);
    }
}
