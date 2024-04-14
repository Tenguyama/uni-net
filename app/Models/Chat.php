<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasUuids;

    use HasFactory;

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::deleting(function (Chat $chat) {
            $chat->consumerChats()->delete();
            $chat->messages()->delete();
        });
    }

    public function consumerChats(): HasMany
    {
        return $this->hasMany(ConsumerChat::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

}
