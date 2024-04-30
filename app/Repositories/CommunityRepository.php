<?php

namespace App\Repositories;

use App\Http\Requests\Community\CommunityRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Community;
use App\Models\CommunityManager;
use App\Models\Consumer;
use App\Models\Follow;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommunityRepository
{
    protected static $instance;

    private readonly Community $model;
    private readonly CommunityManager $communityManager;
    private readonly Consumer $consumer;
    private readonly Follow $follow;
    private readonly Post $post;

    protected function __construct(){
        $this->model = new Community();
        $this->communityManager = new CommunityManager();
        $this->consumer = new Consumer();
        $this->follow = new Follow();
        $this->post = new Post();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function save(CommunityRequest $request){
        $consumerId = Auth::user()->id;
        $communityId = $request->has('id') ? $request->input('id') : null;
        $params = [
            'nickname' => $request->input('nickname'),
            'description' => $request->input('description'),
            'consumer_id' => $consumerId,
            'is_locked' => $request->input('is_locked'),
        ];

        if(isset($communityId) and !empty($communityId)){
            $existsCommunity = $this->model->query()
                ->where('id', '=', $communityId)
                ->where('consumer_id','=',$consumerId)
                ->exists();
            if($existsCommunity){
                $community = $this->model->query()->updateOrCreate(['id'=>$communityId], $params);
            }else{
                $community = $this->model->query()->create($params);
            }
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
        $consumerId  = Auth::user()->id;
        if($community->consumer_id == $consumerId){
            return $community->delete();
        }else{
            return ['message' => 'Community not found'];
        }
    }

    public function search(SearchRequest $request){
        return $this->model->query()
            ->where('nickname', 'like', '%' . $request->input('query') . '%')
            ->with('media')
            ->get();
    }


    public function getProfile(Community $community){
        $consumerId = Auth::check() ? Auth::user()->id : null;

        $thisConsumerFollow = $this->follow->query()
            ->where('consumer_id','=',$consumerId)
            ->where('trackable_id','=',$community->id)
            ->where('trackable_type','=',Community::class)
            ->exists();

        $thisConsumerIsAuthor = ($community->consumer_id == $consumerId);
        $thisConsumerIdManager = $this->communityManager->query()
            ->where('community_id','=',$community->id)
            ->where('consumer_id','=',$consumerId)
            ->exists();
        // Кількість модераторів групи
        $communityManagersCount = $this->communityManager->query()
            ->where('community_id','=',$community->id)
            ->count();
        // Кількість підписок на групу
        $followCommunityCount = $this->follow->query()
            ->where('trackable_id','=',$community->id)
            ->where('trackable_type','=',Community::class)
            ->count();

        $community->load('consumer', 'communityManagers', 'communityManagers.consumer');

        // Перевірка на блокування профілю
        if($community->is_locked){
            if($thisConsumerIsAuthor
                or $thisConsumerIdManager
                or $thisConsumerFollow){
                $returnPost = true;
            }else{
                $returnPost = false;
            }
        } else {
            $returnPost = true;
        }

        // Отримання публікацій групи
        if ($returnPost) {
            $posts = $this->post->query()->with([
                'postLikes' => function ($query) {
                    $query->selectRaw('post_id, COUNT(*) as total')
                        ->selectRaw("SUM(is_liked = '1') as likes")
                        ->selectRaw("SUM(is_liked = '0') as dislikes")
                        ->groupBy('post_id');
                    },
                'thisConsumerLiked',
                'media',
                'postable' => function ($query) {
                    $query->with(['media'])->select(['id', 'nickname']);
                }])->where('postable_id' ,'=', $community->id)->latest()->get();
            $posts->each(function ($post) {
                $post->author_details = [
                    'id' => $post->postable->id,
                    'nickname' => $post->postable->nickname,
                    'type' => $post->postable_type instanceof Community ? 'community' : 'consumer',
                    'avatar' => $post->postable->media ? $post->postable->media->path : null
                ];
                unset($post->postable_id);
                unset($post->postable_type);
                unset($post->postable);
            });
        } else {
            $posts = [
                'message' => 'This profile is locked!',
            ];
        }

        return [
            'community' => $community,
            'this_consumer_follow' => $thisConsumerFollow,
            'this_consumer_is_author' => $thisConsumerIsAuthor,
            'this_consumer_is_manager' => $thisConsumerIdManager,
            'community_managers_count' => $communityManagersCount,
            'follow_community_count' => $followCommunityCount,
            'posts' => $posts,
        ];
    }
}
