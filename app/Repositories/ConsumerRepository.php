<?php

namespace App\Repositories;

use App\Http\Requests\Consumer\ConsumerLoginRequest;
use App\Http\Requests\Consumer\ConsumerRequest;
use App\Http\Requests\Consumer\ConsumerSocialiteRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Consumer;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class ConsumerRepository
{
    protected static $instance;

    private readonly Consumer $model;

    protected function __construct(){
        $this->model = new Consumer();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function save(ConsumerSocialiteRequest $request){
        $provider = $request->input('provider');
        $validated = $this->validateProvider($provider);
        if (!is_null($validated))
            return $validated;
        $providerUser = Socialite::driver($provider)->userFromToken($request->input('access_provider_token'));
        $consumer = $this->model->query()->firstOrCreate(
            [
                'email' => $providerUser->getEmail()
            ],
            [
                'name' => $providerUser->getName(),
            ]
        );

        if (!$consumer->media()->exists()) {
            $media = new Media();
            $media->mediable_id = $consumer->id;
            $media->mediable_type = $this->model::class;
            //тут я не впевнений, може такого і нема будем тестити
            //але точно можна витягнути, питання тільки як
            $media->path = $providerUser->getAvatar();
            $media->save();
        }

        $consumer->load('media');

        return  [
            'token' => $consumer->createToken('Sanctom+Socialite')->plainTextToken,
            'consumer' => $consumer,
            //'consumer' => $consumer->makeHidden('password')->toArray(),
        ];
    }
    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['google'])) {
            return ["message" => 'You can only login via google account'];
        }
    }

    public function login(ConsumerLoginRequest $request){
        $consumer = $this->model->query()->where('email', $request->input('email'))->first();

        if(!$consumer || ! Hash::check($request->input('password'), $consumer->password)){
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }

        $consumer->load('media');

        return [
            'token'=>$consumer->createToken('Sanctum')->plainTextToken,
            'consumer' => $consumer,
            //'consumer' => $consumer->makeHidden('password')->toArray(),
        ];
    }

    public function logout(){
        return Auth::user()->currentAccessToken()->delete();
        //або, треба тестити
        //Auth::user()->currentAccessToken()->delete();
        //return null;
    }

    public function update(ConsumerRequest $request) {
        $consumer = Auth::user();

        $consumer->name = $request->input('name');
        $consumer->status = $request->input('status');
        $consumer->description = $request->input('description');
        $consumer->fakult_id = $request->input('fakult_id');
        $consumer->group_id = $request->input('group_id');
        $consumer->telegram_nickname = $request->input('telegram_nickname');
        $consumer->is_locked = $request->input('is_locked');

        $request->has('nickname') ? $consumer->nickname = $request->input('nickname') : null;
        $request->has('email') ? $consumer->email = $request->input('email') : null;
        $request->has('password') ? $consumer->password = $request->input('password') : null;

        $consumer->save();

        if ($request->has('avatar')) {
            $avatarUrl = $request->input('avatar');
            $media = $consumer->media()->first();
            if (isset($media) and !empty($media)) {
                $media->path = $avatarUrl;
                $media->save();
            } else {
                $media = new Media();
                $media->mediable_id = $consumer->id;
                $media->mediable_type = $this->model::class;
                $media->path = $avatarUrl;
                $media->save();
            }
        }
        $consumer->load('media');
        return $consumer;
    }


    public function delete(){
        $consumer = $this->model->query()->where('id', '=',Auth::user()->id)->first();
        if(isset($consumer) and !empty($consumer)){
            return $consumer->delete();
        }else{
            return false;
        }
    }

    public function search(SearchRequest $request){
        return $this->model->query()
            ->where('nickname', 'like', '%' . $request->input('query') . '%')
            ->orWhere('email', 'like', '%' . $request->input('query') . '%')
            ->with('media')
            ->get();
    }
}
