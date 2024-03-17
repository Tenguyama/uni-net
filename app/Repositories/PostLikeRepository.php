<?php

namespace App\Repositories;

use App\Http\Requests\PostLike\PostLikeRequest;
use App\Models\PostLike;
use Illuminate\Support\Facades\Auth;

class PostLikeRepository
{
    protected static $instance;

    private readonly PostLike $model;

    protected function __construct(){
        $this->model = new PostLike();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function save(PostLikeRequest $request){
        $consumerId = Auth::user()->id;
        $params = [
            'consumer_id' => $consumerId,
            'post_id' => $request->input('post_id'),
            'is_liked' => $request->input('is_liked'),
        ];
        $postLike = $this->model->query()
            ->where('consumer_id','=', $consumerId)
            ->where('post_id','=', $params['post_id'])
            ->first();

        if(isset($postLike) and !empty($postLike)){
            return $this->model->query()->updateOrCreate(['id'=>$postLike->id], $params);
        }else{
            return $this->model->query()->create($params);
        }
    }

    public function delete(PostLikeRequest $request){
        $consumerId = Auth::user()->id;
        $postLike = $this->model->query()
            ->where('consumer_id','=', $consumerId)
            ->where('post_id','=',  $request->input('post_id'))
            ->first();

        if(isset($postLike) and !empty($postLike)){
            return $postLike->delete();
        }else{
            return false;
        }
    }
}
