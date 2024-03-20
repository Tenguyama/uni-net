<?php

namespace App\Enums;

use App\Models\Comment;
use App\Models\Post;

enum ComplaintTypeEnum:string
{
    case POST = 'post';
    case COMMENT = 'comment';

    /**
     * Gets the full namespace of the model based on the complaint type.
     *
     * @return string
     */
    public function modelClass(): string
    {
        return match($this) {
            self::POST => Post::class,
            self::COMMENT => Comment::class,
        };
    }
}
