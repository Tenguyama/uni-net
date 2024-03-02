<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentLike extends Model
{
    use HasUuids;

    use HasFactory;

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = false;

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function consumer(): BelongsTo
    {
        return $this->belongsTo(Consumer::class);
    }
}
