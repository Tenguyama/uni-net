<?php

namespace App\Services;

use App\Models\Language;
use App\Repositories\ThemeRepository;

class ThemeService
{
    protected static $instance;

    private readonly ThemeRepository $repository;

    protected function __construct(){
        $this->repository = ThemeRepository::getInstance();
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
}
