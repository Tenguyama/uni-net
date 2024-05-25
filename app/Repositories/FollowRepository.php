<?php

namespace App\Repositories;

use App\Enums\FollowTypeEnum;
use App\Http\Requests\Follow\FollowRequest;
use App\Models\Community;
use App\Models\Consumer;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;

class FollowRepository
{
    protected static $instance;

    private readonly Follow $model;
    private readonly Consumer $consumer;

    protected function __construct(){
        $this->model = new Follow();
        $this->consumer = new Consumer();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function follow(FollowRequest $request){
        if(Auth::check()){
            $consumerId = Auth::user()->id;
            $type = $request->input('trackable_type');
            $followType = FollowTypeEnum::from($type);
            $params = [
                'trackable_id' => $request->input('trackable_id'),
                'trackable_type' => $followType->modelClass(),
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
        }else{
            return [
                'message' => 'not valid authorize token'
            ];
        }

    }

    public function getFollowers(FollowRequest $request)
    {
        $params = [
            'id' => $request->input('trackable_id'),
            'type' => $request->input('trackable_type'),
        ];
        $followers = $this->model->query()
            ->where('trackable_id', $params['id'])
            ->where('trackable_type', $params['type'])
            ->pluck('consumer_id');

        return $this->consumer->query()
            ->whereIn('id', $followers)
            ->with('media')
            ->get();
    }

    public function getTrackable(Consumer $consumer)
    {
        $communities = $consumer->followers()
            ->where('trackable_type', Community::class)
            ->with('trackable.media')
            ->get()
            ->pluck('trackable');

        $consumers = $consumer->followers()
            ->where('trackable_type', Consumer::class)
            ->with('trackable.media')
            ->get()
            ->pluck('trackable');

        return [
            'communities' => $communities,
            'consumers' => $consumers,
        ];
    }


}
