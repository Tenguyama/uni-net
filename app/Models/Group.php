<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasUuids;

    use HasFactory;

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = false;

    public function groupDescriptions(): HasMany
    {
        return $this->hasMany(GroupDescription::class);
    }

    public function consumers(): HasMany
    {
        return $this->hasMany(Consumer::class);
    }
    public function fakults(): BelongsToMany
    {
        return $this->belongsToMany(Fakult::class, 'facult_group');
    }
}
