<?php

namespace App\Repositories;

use App\Http\Requests\CommentLike\CommentLikeRequest;
use App\Models\CommentLike;
use Illuminate\Support\Facades\Auth;

class CommentLikeRepository
{
    protected static $instance;

    private readonly CommentLike $model;

    protected function __construct(){
        $this->model = new CommentLike();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function save(CommentLikeRequest $request){
        $consumerId = Auth::user()->id;
        $params = [
            'consumer_id' => $consumerId,
            'comment_id' => $request->input('comment_id'),
            'is_liked' => $request->input('is_liked'),
        ];
        $commentLike = $this->model->query()
            ->where('consumer_id','=', $consumerId)
            ->where('comment_id','=', $params['comment_id'])
            ->first();

        if(isset($commentLike) and !empty($commentLike)){
            return $this->model->query()->updateOrCreate(['id'=>$commentLike->id], $params);
        }else{
            return $this->model->query()->create($params);
        }
    }

    public function delete(CommentLikeRequest $request){
        $consumerId = Auth::user()->id;
        $commentLike = $this->model->query()
            ->where('consumer_id','=', $consumerId)
            ->where('comment_id','=',  $request->input('comment_id'))
            ->first();

        if(isset($commentLike) and !empty($commentLike)){
            return $commentLike->delete();
        }else{
            return false;
        }
    }
}
