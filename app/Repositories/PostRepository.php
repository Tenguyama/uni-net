<?php

namespace App\Repositories;

use App\Enums\PostTypeEnum;
use App\Http\Requests\Post\PostRequest;
use App\Models\Community;
use App\Models\Consumer;
use App\Models\Follow;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostRepository
{
    protected static $instance;

    private readonly Post $model;
    private readonly Follow $follow;
    private readonly Community $community;

    protected function __construct(){
        $this->model = new Post();
        $this->follow = new Follow();
        $this->community = new Community();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getAllRecommendationPost()
    {
        $consumerId = Auth::user()->id;
        $query = $this->model->query()->with([
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
            }
        ]);
        $posts = $query->where('postable_id' ,'!=', $consumerId)
            ->latest()->get();
        $posts->each(function ($post) {
            $post->author_details = [
                'id' => $post->postable->id,
                'nickname' => $post->postable->nickname,
                'type' => $post->postable_type instanceof Consumer ? 'consumer' : 'community',
                'avatar' => $post->postable->media ? $post->postable->media->path : null
            ];
            unset($post->postable_id);
            unset($post->postable_type);
            unset($post->postable);
        });
        return $posts;
    }

    public function getAllPostByTrackable()
    {
        $consumerId = Auth::user()->id;
        $follows = $this->follow->query()
            ->where('follower_id', '=', $consumerId)
            ->get(['trackable_id', 'trackable_type']);
        $query = $this->model->query()->with([
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
            }
        ]);
        $query->where(function ($q) use ($follows) {
            foreach ($follows as $follow) {
                $q->orWhere(function ($subQuery) use ($follow) {
                    $subQuery->where('postable_id', $follow->trackable_id)
                        ->where('postable_type', $follow->trackable_type->modelClass());
                });
            }
        });
        $posts = $query->latest()->get();
        $posts->each(function ($post) {
            $post->author_details = [
                'id' => $post->postable->id,
                'nickname' => $post->postable->nickname,
                'type' => $post->postable_type instanceof Consumer ? 'consumer' : 'community',
                'avatar' => $post->postable->media ? $post->postable->media->path : null
            ];
            unset($post->postable_id);
            unset($post->postable_type);
            unset($post->postable);
        });
        return $posts;
    }

    public function getPostById(Post $post)
    {
        $findPost = $this->model->query()->with([
            'postLikes' => function ($query) {
                $query->selectRaw('post_id, COUNT(*) as total')
                    ->selectRaw("SUM(is_liked = '1') as likes")
                    ->selectRaw("SUM(is_liked = '0') as dislikes")
                    ->groupBy('post_id');
            },
            'thisConsumerLiked',
            'media',
            'comments' => function ($query) {
                $query->with([
                    'consumer' => function ($query) {
                        $query->with('media');
                    },
                    'thisConsumerLiked',
                    'commentLikes' => function ($query) {
                        $query->selectRaw('comment_id, COUNT(*) as total')
                            ->selectRaw("SUM(is_liked = '1') as likes")
                            ->selectRaw("SUM(is_liked = '0') as dislikes")
                            ->groupBy('comment_id');
                    },
                ])->oldest();
                // або
                //])->orderBy('created_at', 'asc');
            },
            'postable' => function ($query) {
                $query->with(['media'])->select(['id', 'nickname']);
            }
        ])->findOrFail($post->id);
        $findPost->postable_type = $findPost->postable->getMorphClass();
        $findPost->author_details = [
            'id' => $findPost->postable->id,
            'nickname' => $findPost->postable->nickname,
            'type' => $findPost->postable_type instanceof Consumer ? 'consumer' : 'community',
            'avatar' => $findPost->postable->media ? $findPost->postable->media->path : null
        ];

        unset($findPost->postable_id);
        unset($findPost->postable_type);
        unset($findPost->postable);

        return $findPost;
    }

    public function save(PostRequest $request, ?Post $post){
        $consumerId = Auth::user()->id;
        $typeNamespase = PostTypeEnum::from($request->input('postable_type'))->modelClass();
        $type = new $typeNamespase();
        $postableId = $request->input('postable_id');

        $postable = $type->query()->find($postableId);

        if (!$postable) {
            return ['message' => 'Postable entity not found'];
        }

        $canModify = false;
        if ($postable instanceof Consumer && $postable->id == $consumerId) {
            $canModify = true;
        } elseif ($postable instanceof Community) {
            $community = $this->community->query()
                ->with('communityManagers')
                ->where('id', $postable->id)
                ->first();
            $isManager = $community->communityManagers->contains('consumer_id', $consumerId);
            if ($community->consumer_id == $consumerId || $isManager) {
                $canModify = true;
            }
        }

        if (!$canModify) {
            return ['message' => 'Unauthorized to modify post'];
        }

        $params = [
            'postable_id' => $request->input('postable_id'),
            'postable_type' => $typeNamespase,
            'theme_id' => $request->input('theme_id'),
            'description' => $request->input('description'),
        ];

        if(isset($post) and !empty($post)){
            $resultPost = $this->model->query()->updateOrCreate(['id'=>$post->id], $params);
        }else{
            $resultPost = $this->model->query()->create($params);
        }

        if ($request->has('media')) {
            $mediaUrl = $request->input('media');
            $media = $resultPost->media()->first();
            if (isset($media) and !empty($media)) {
                $media->path = $mediaUrl;
                $media->save();
            } else {
                $media = new Media();
                $media->mediable_id = $resultPost->id;
                $media->mediable_type = $this->model::class;
                $media->path = $mediaUrl;
                $media->save();
            }
        }
        $resultPost->load('media');
        return $resultPost;
    }

    public function delete(Post $post){
        $consumerId = Auth::user()->id;
        $postable = $post->postable;

        if ($postable instanceof Consumer) {
            if($postable->id == $consumerId){
                return $post->delete();
            }else{
                return ['message' => 'Post not found'];
            }
        } elseif ($postable instanceof Community) {
            $communityId = $postable->id;
            $community = $this->community->query()
                ->with('communityManagers')
                ->where('id', $communityId)
                ->first();
            $isManager = $community->communityManagers->contains('consumer_id', $consumerId);
            if($community->consumer_id == $consumerId or $isManager){
                return $post->delete();
            }else{
                return ['message' => 'Post not found'];
            }
        }else{
            return ['message' => 'Post not found'];
        }
    }
}
