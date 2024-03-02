<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Consumer extends Model
{
    use HasUuids;

    use HasFactory;

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = false;

    public function fakult(): HasOne
    {
        return $this->hasOne(Fakult::class);
    }

    public function group(): HasOne
    {
        return $this->hasOne(Group::class);
    }
    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }

    public function commentLikes(): HasMany
    {
        return $this->hasMany(CommentLike::class);
    }

    public function communities(): HasMany
    {
        return $this->hasMany(Community::class);
    }

    public function communityManagers(): HasMany
    {
        return $this->hasMany(CommunityManager::class);
    }

    public function consumerChats(): HasMany
    {
        return $this->hasMany(ConsumerChat::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function posts(): MorphMany
    {
        return $this->morphMany(Post::class, 'postable');
    }

    //polimorph
    public function followers(): MorphMany
    {
        return $this->morphMany(Follow::class,'trackable');
    }
}
