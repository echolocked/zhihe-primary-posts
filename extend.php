<?php

use Flarum\Extend;
use Zhihe\PrimaryPosts\Api\Controller\MarkPrimaryController;
use Zhihe\PrimaryPosts\Api\Controller\UnmarkPrimaryController;
use Zhihe\PrimaryPosts\Api\Serializer\PostSerializer;
use Zhihe\PrimaryPosts\Listener\ProcessPrimaryPostData;

return [

    (new Extend\Routes('api'))
        ->post('/posts/{id}/mark-primary', 'posts.mark-primary', MarkPrimaryController::class)
        ->delete('/posts/{id}/unmark-primary', 'posts.unmark-primary', UnmarkPrimaryController::class),

    (new Extend\ApiSerializer(\Flarum\Api\Serializer\PostSerializer::class))
        ->attributes(PostSerializer::class),

    (new Extend\Event())
        ->listen(\Flarum\Discussion\Event\Saving::class, [ProcessPrimaryPostData::class, 'handleDiscussion'])
        ->listen(\Flarum\Post\Event\Saving::class, [ProcessPrimaryPostData::class, 'handlePost']),

    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),

    (new Extend\Locales(__DIR__.'/locale')),
];