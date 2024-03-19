<?php

namespace App\Repositories;

use App\Http\Requests\Language\LanguageRequest;
use App\Models\Language;

class LanguageRepository
{
    protected static $instance;

    private readonly Language $model;

    protected function __construct(){
        $this->model = new Language();
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

    public function save(LanguageRequest $request){
        $id = $request->input('id');
        $params = [
            'locale' => $request->input('locale'),
        ];
        $language = $this->model->query()
            ->where('id','=', $id)
            ->first();

        if(isset($language) and !empty($language)){
            return $this->model->query()->updateOrCreate(['id'=>$id], $params);
        }else{
            return $this->model->query()->create($params);
        }
    }

    public function delete(LanguageRequest $request){
        $id = $request->input('id');
        $locale = $request->input('locale');

        if (isset($id) and !empty($id)) {
            $language = $this->model->query()->where('id', $id)->first();
        } elseif (isset($locale) and !empty($locale)) {
            $language = $this->model->query()->where('locale', $locale)->first();
        }

        if (isset($language) and !empty($language)) {
            return $language->delete();
        } else {
            return ['message' => 'Language not found'];
        }
    }

    public function getAll()
    {
        return $this->model->query()->get();
    }
}
