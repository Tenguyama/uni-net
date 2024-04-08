<?php

namespace App\Repositories;

use App\Http\Requests\Group\GroupGetRequest;
use App\Models\Consumer;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class GroupRepository
{
    protected static $instance;

    private readonly Group $model;
    private readonly Consumer $consumer;

    protected function __construct(){
        $this->model = new Group();
        $this->consumer = new Consumer();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getAll(GroupGetRequest $request){
        $fakultId = $request->input('fakult_id');
        $languageId = $request->input('language_id');

        return $this->model->query()
            ->join('group_descriptions','group_descriptions.group_id','=','groups.id')
            ->select('groups.id as id',
                'group_descriptions.name as name',
                'groups.fakult_id as fakult_id',
                'group_descriptions.language_id as language_id',
            )->where('fakult_id','=',$fakultId)
            ->where('language_id','=',$languageId)
            ->get();
    }

    public function select(Group $group){
        $consumerId = Auth::user()->id;
        $consumer = $this->consumer->query()
            ->where('id','=',$consumerId)
            ->first();

        if(isset($consumer) and !empty($consumer)){
            $consumer->group_id = $group->id;
            return $consumer->save();
        }else{
            return false;
        }
    }

}
