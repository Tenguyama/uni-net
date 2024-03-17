<?php

namespace App\Services;

use App\Http\Requests\Consumer\ConsumerLoginRequest;
use App\Http\Requests\Consumer\ConsumerRequest;
use App\Http\Requests\Consumer\ConsumerSocialiteRequest;
use App\Repositories\ConsumerRepository;
use Illuminate\Http\JsonResponse;

class ConsumerService
{
    protected static $instance;

    private readonly ConsumerRepository $repository;

    protected function __construct(){
        $this->repository = ConsumerRepository::getInstance();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function registerWithProvider(ConsumerSocialiteRequest $request){
        return $this->repository->save($request);
    }
    public function loginWithProvider(ConsumerSocialiteRequest $request){
        return $this->repository->save($request);
    }

    public function login(ConsumerLoginRequest $request){
        return $this->repository->login($request);
    }

    public function logout(){
        return $this->repository->logout();
    }

    public function update(ConsumerRequest $request){
        return $this->repository->update($request);
    }
    public function delete(){
        return $this->repository->delete();
    }
}
