<?php

namespace App\Repositories;

use App\Models\Language;
use App\Models\Theme;

class ThemeRepository
{
    protected static $instance;

    private readonly Theme $model;

    protected function __construct(){
        $this->model = new Theme();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getAllByLanguage(Language $language){
        return $this->model->query()
            ->join('theme_descriptions','theme_descriptions.theme_id','=','themes.id')
            ->select('themes.id as id',
                'theme_descriptions.name as name',
                'theme_descriptions.language_id as language_id',
            )->where('language_id','=',$language->id)
            ->get();
    }

}
