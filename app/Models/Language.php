<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
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

    public function fakultDescriptions(): HasMany
    {
        return $this->hasMany(FakultDescription::class);
    }

    public function themeDescriptions(): HasMany
    {
        return $this->hasMany(ThemeDescription::class);
    }
    public function alertTypeDescriptions(): HasMany
    {
        return $this->hasMany(AlertTypeDescription::class);
    }
}
