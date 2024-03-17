<?php

namespace App\Repositories;

use App\Http\Requests\Community\CommunityRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Community;
use Illuminate\Support\Facades\Auth;

class CommunityRepository
{
    protected static $instance;

    private readonly Community $model;

    protected function __construct(){
        $this->model = new Community();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function save(CommunityRequest $request, ?string $id){
        $consumerId = Auth::user()->id;
        $params = [
            'nickname' => $request->input('nickname'),
            'description' => $request->input('description'),
            'consumer_id' => $consumerId,
            'is_locked' => $request->input('is_locked'),
        ];

        if(isset($id) and !empty($id)){
            return $this->model->query()->updateOrCreate(['id'=>$id], $params);
        }else{
            return $this->model->query()->create($params);
        }
    }

    public function delete(Community $community){
        return $community->delete();
    }

    public function search(SearchRequest $request){
        return $this->model->query()
            ->where('nickname', 'like', '%' . $request->input('query') . '%')
            ->get();
    }
}
