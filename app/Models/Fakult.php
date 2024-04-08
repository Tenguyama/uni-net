<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fakult extends Model
{
    use HasUuids;

    use HasFactory;

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = false;

    public function fakultDescription(): HasMany
    {
        return $this->hasMany(FakultDescription::class);
    }
    public function consumers(): HasMany
    {
        return $this->hasMany(Consumer::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }
}
