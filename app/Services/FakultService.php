<?php

namespace App\Services;

use App\Models\Fakult;
use App\Models\Language;
use App\Repositories\FakultRepository;

class FakultService
{
    protected static $instance;

    private readonly FakultRepository $repository;

    protected function __construct(){
        $this->repository = FakultRepository::getInstance();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getAllByLanguage(Language $language)
    {
        return $this->repository->getAllByLanguage($language);
    }

    public function select(Fakult $fakult){
        return $this->repository->select($fakult);
    }
}
