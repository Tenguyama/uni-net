<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Community extends Model
{
    use HasUuids;

    use HasFactory;

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::deleting(function (Community $community) {
            $community->media()->delete();
        });
    }
    public function consumer(): BelongsTo
    {
        return $this->belongsTo(Consumer::class);
    }

    public function communityManagers(): HasMany
    {
        return $this->hasMany(CommunityManager::class);
    }
    //polimorph
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
