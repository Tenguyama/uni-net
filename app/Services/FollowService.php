<?php

namespace App\Services;

use App\Http\Requests\Follow\FollowRequest;
use App\Repositories\FollowRepository;

class FollowService
{
    protected static $instance;

    private readonly FollowRepository $repository;

    protected function __construct(){
        $this->repository = FollowRepository::getInstance();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function follow(FollowRequest $request){
        return $this->repository->follow($request);
    }
}
