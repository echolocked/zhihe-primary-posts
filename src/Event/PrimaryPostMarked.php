<?php

namespace Zhihe\PrimaryPosts\Event;

use Flarum\Post\Post;

class PrimaryPostMarked
{
    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }
}