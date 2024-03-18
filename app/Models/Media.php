<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    use HasUuids;

    use HasFactory;

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $hidden = ['id', 'mediable_id', 'mediable_type'];

    public $timestamps = false;

    //polimorph
    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
