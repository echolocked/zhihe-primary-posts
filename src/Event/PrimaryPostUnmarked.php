<?php

namespace Zhihe\PrimaryPosts\Event;

use Flarum\Post\Post;

class PrimaryPostUnmarked
{
    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }
}