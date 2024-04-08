<?php

namespace App\Repositories;

use App\Http\Requests\Comment\CommentRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentRepository
{
    protected static $instance;

    private readonly Comment $model;

    protected function __construct(){
        $this->model = new Comment();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function save(CommentRequest $request){
        $consumerId = Auth::user()->id;
        $id = $request->input('id');
        $params = [
            'post_id' => $request->input('post_id'),
            'consumer_id' => $consumerId,
            'parent_id' => $request->input('parent_id'),
            'body' => $request->input('body'),
        ];
        $comment = $this->model->query()
            ->where('id','=', $id)
            ->first();

        if(isset($comment) and !empty($comment)){
            return $this->model->query()->updateOrCreate(['id'=>$id], $params);
        }else{
            return $this->model->query()->create($params);
        }
    }

    public function delete(Comment $comment){
        $consumerId = Auth::user()->id;

        if ($comment->consumer()->id == $consumerId) {
            return $comment->delete();
        } else {
            return ['message' => 'Comment not found'];
        }
    }

}
