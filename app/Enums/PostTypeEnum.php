<?php

namespace App\Enums;

use App\Models\Community;
use App\Models\Consumer;

enum PostTypeEnum: string
{
    case CONSUMER = 'consumer';
    case COMMUNITY = 'community';

    /**
     * Gets the full namespace of the model based on the complaint type.
     *
     * @return string
     */
    public function modelClass(): string
    {
        return match($this) {
            self::CONSUMER => Consumer::class,
            self::COMMUNITY => Community::class,
        };
    }

    public static function fromModelClass(string $modelClass): ?PostTypeEnum
    {
        return match($modelClass) {
            Consumer::class => self::CONSUMER,
            Community::class => self::COMMUNITY,
            default => null,
        };
    }
}
