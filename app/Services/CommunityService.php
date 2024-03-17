<?php

namespace App\Services;

use App\Http\Requests\Community\CommunityRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Community;
use App\Repositories\CommunityRepository;

class CommunityService
{
    protected static $instance;

    private readonly CommunityRepository $repository;

    protected function __construct(){
        $this->repository = CommunityRepository::getInstance();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function create(CommunityRequest $request){
        return $this->repository->save($request, null);
    }

    public function update(CommunityRequest $request, string $id){
        return $this->repository->save($request, $id);
    }
    public function delete(Community $community){
        return $this->repository->delete($community);
    }

    public function search(SearchRequest $request){
        return $this->repository->search($request);
    }
}
