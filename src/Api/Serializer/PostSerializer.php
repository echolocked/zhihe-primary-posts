<?php

namespace Zhihe\PrimaryPosts\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;
use Flarum\Post\Post;

class PostSerializer
{
    public function __invoke($serializer, $model, $attributes): array
    {
        if ($model instanceof Post) {
            $attributes['isPrimary'] = (bool) $model->is_primary;
            $attributes['primaryNumber'] = $model->primary_number;
        }

        return $attributes;
    }
}