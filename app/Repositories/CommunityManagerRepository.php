<?php

namespace App\Repositories;

use App\Http\Requests\ComminityManager\CommunityManagerRequest;
use App\Models\Community;
use App\Models\CommunityManager;
use Illuminate\Support\Facades\Auth;

class CommunityManagerRepository
{
    protected static $instance;

    private readonly CommunityManager $model;
    private readonly Community $community;

    protected function __construct(){
        $this->model = new CommunityManager();
        $this->community = new Community();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function create(CommunityManagerRequest $request){
        $consumerId = Auth::user()->id;
        $params = [
            'consumer_id' => $request->input('consumer_id'),
            'community_id' => $request->input('community_id'),
        ];
        $community = $this->community->query()
            ->where('id','=',$params['community_id'])
            ->where('consumer_id','=',$consumerId)
            ->first();
        if(isset($community) and !empty($community)){
            $communityManager = $this->model->query()
                ->where('consumer_id','=', $params['consumer_id'])
                ->where('community_id','=', $params['community_id'])
                ->first();
            if(isset($communityManager) and !empty($communityManager)){
                return ['message'=>'Such a community manager already exists'];
            }else{
                return $this->model->query()->create($params);
            }
        }else{
            return ['message'=>'Authorized user, not community author'];
        }
    }

    public function delete(CommunityManagerRequest $request){
        $consumerId = Auth::user()->id;
        $params = [
            'consumer_id' => $request->input('consumer_id'),
            'community_id' => $request->input('community_id'),
        ];
        $community = $this->community->query()
            ->where('id','=',$params['community_id'])
            ->where('consumer_id','=',$consumerId)
            ->first();
        if(isset($community) and !empty($community)){
            $communityManager = $this->model->query()
                ->where('consumer_id','=', $params['consumer_id'])
                ->where('community_id','=', $params['community_id'])
                ->first();
            if(isset($communityManager) and !empty($communityManager)){
                return $this->model->query()->delete();
            }else{
                return ['message'=>'Such a community manager does not exist'];
            }
        }else{
            return ['message'=>'Authorized user, not community author'];
        }
    }

}
