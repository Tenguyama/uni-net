<?php

namespace App\Services;

use App\Http\Requests\Language\LanguageRequest;
use App\Repositories\LanguageRepository;

class LanguageService
{
    protected static $instance;

    private readonly LanguageRepository $repository;

    protected function __construct(){
        $this->repository = LanguageRepository::getInstance();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    //ONLY FOR ADMIN PANEL
    //create
    //update
    //delete

    //API
    //getAll
    public function create(LanguageRequest $request){
        return $this->repository->save($request);
    }

    public function update(LanguageRequest $request){
        return $this->repository->save($request);
    }
    public function delete(LanguageRequest $request){
        return $this->repository->delete($request);
    }
    public function getAll(){
        return $this->repository->getAll();
    }
}
