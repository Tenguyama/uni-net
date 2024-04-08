<?php

namespace App\Repositories;

use App\Models\Consumer;
use App\Models\Fakult;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;

class FakultRepository
{
    protected static $instance;

    private readonly Fakult $model;
    private readonly Consumer $consumer;

    protected function __construct(){
        $this->model = new Fakult();
        $this->consumer = new Consumer();
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
            ->join('fakult_descriptions','fakult_descriptions.fakult_id','=','fakults.id')
            ->select('fakults.id as id',
                'fakult_descriptions.name as name',
                'fakult_descriptions.language_id as language_id',
            )->where('language_id','=',$language->id)
            ->get();
    }

    public function select(Fakult $fakult){
        $consumerId = Auth::user()->id;
        $consumer = $this->consumer->query()
            ->where('id','=',$consumerId)
            ->first();

        if(isset($consumer) and !empty($consumer)){
            $consumer->fakult_id = $fakult->id;
            return $consumer->save();
        }else{
            return false;
        }
    }

}
