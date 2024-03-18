<?php

namespace App\Repositories;

use App\Http\Requests\Community\CommunityRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Community;
use App\Models\Media;
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
            $community = $this->model->query()->updateOrCreate(['id'=>$id], $params);
        }else{
            $community = $this->model->query()->create($params);
        }

        if ($request->has('avatar')) {
            $avatarUrl = $request->input('avatar');
            $media = $community->media()->first();
            if (isset($media) and !empty($media)) {
                $media->path = $avatarUrl;
                $media->save();
            } else {
                $media = new Media();
                $media->mediable_id = $community->id;
                $media->mediable_type = $this->model::class;
                $media->path = $avatarUrl;
                $media->save();
            }
        }
        $community->load('media');
        return $community;
    }

    public function delete(Community $community){
        return $community->delete();
    }

    public function search(SearchRequest $request){
        return $this->model->query()
            ->where('nickname', 'like', '%' . $request->input('query') . '%')
            ->with('media')
            ->get();
    }
}
