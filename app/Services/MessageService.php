<?php

namespace App\Services;

use App\Http\Requests\Message\MessageRequest;
use App\Repositories\MessageRepository;

class MessageService
{
    protected static $instance;

    private readonly MessageRepository $repository;

    protected function __construct(){
        $this->repository = MessageRepository::getInstance();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function create(MessageRequest $request){
        return $this->repository->save($request);
    }

    public function update(MessageRequest $request){
        return $this->repository->save($request);
    }
    public function delete(MessageRequest $request){
        return $this->repository->delete($request);
    }
}
