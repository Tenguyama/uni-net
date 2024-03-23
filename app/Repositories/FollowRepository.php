<?php

namespace App\Repositories;

use App\Http\Requests\Complaint\ComplaintRequest;
use App\Http\Requests\Follow\FollowRequest;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;

class FollowRepository
{
    protected static $instance;

    private readonly Follow $model;

    protected function __construct(){
        $this->model = new Follow();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function follow(FollowRequest $request){
        $consumerId = Auth::user()->id;
        $type = $request->input('trackable_type');
        $params = [
            'trackable_id' => $request->input('trackable_id'),
            'trackable_type' => $type->modelClass(),
            'follower_id' => $consumerId,
        ];

        $follow = $this->model->query()
            ->where('trackable_id', '=', $params['trackable_id'])
            ->where('trackable_type', '=', $params['trackable_type'])
            ->where('follower_id', '=', $params['follower_id'])
            ->first();

        if(isset($follow) and !empty($follow)){
            return $follow->delete();
        }else{
            return $this->model->query()->create($params);
        }
    }


}
