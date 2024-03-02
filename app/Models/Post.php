<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Post extends Model
{
    use HasUuids;

    use HasFactory;

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = true;

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function postLikes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }
    //polimorph
    public function postable(): MorphTo
    {
        return $this->morphTo();
    }

    public function media(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    //polimorph
    public function complaints(): MorphMany
    {
        return $this->morphMany(Complaint::class, 'complaintable');
    }
}
