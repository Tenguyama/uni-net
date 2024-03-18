<?php

namespace App\Services;

use App\Http\Requests\ComminityManager\CommunityManagerRequest;
use App\Repositories\CommunityManagerRepository;

class CommunityManagerService
{
    protected static $instance;

    private readonly CommunityManagerRepository $repository;

    protected function __construct(){
        $this->repository = CommunityManagerRepository::getInstance();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function create(CommunityManagerRequest $request){
        return $this->repository->create($request);
    }

    public function delete(CommunityManagerRequest $request){
        return $this->repository->delete($request);
    }

}
