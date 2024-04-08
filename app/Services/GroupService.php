<?php

namespace App\Services;

use App\Http\Requests\Group\GroupGetRequest;
use App\Models\Group;
use App\Repositories\GroupRepository;

class GroupService
{
    protected static $instance;

    private readonly GroupRepository $repository;

    protected function __construct(){
        $this->repository = GroupRepository::getInstance();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getAll(GroupGetRequest $request)
    {
        return $this->repository->getAll($request);
    }

    public function select(Group $group){
        return $this->repository->select($group);
    }
}
