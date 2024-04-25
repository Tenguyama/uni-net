<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Consumer extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        HasUuids,
        Notifiable;

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = false;

    protected $hidden = ['password',];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn (Consumer $consumer) =>  $consumer->nickname = explode('@', $consumer->email)[0]);
        static::deleting(function (Consumer $consumer) {
            $consumer->tokens()->delete();
            $consumer->media()->delete();
            //тут дописати
        });
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Hash::make($value)
        );
    }


    public function fakult(): BelongsTo
    {
        return $this->belongsTo(Fakult::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
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

    //polimorph
    public function media(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
