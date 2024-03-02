<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends Model
{
    use HasUuids;

    use HasFactory;

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = false;

    public function themeDescription(): HasMany
    {
        return $this->hasMany(ThemeDescription::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Theme::class, 'parent_id');
    }

    public function childrens(): HasMany
    {
        return $this->hasMany(Theme::class, 'parent_id');
    }
}
