<?php

namespace Zhihe\PrimaryPosts\Api\Controller;

use Flarum\Api\Controller\AbstractDeleteController;
use Flarum\Http\RequestUtil;
use Flarum\Post\Post;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Validation\ValidationException;
use Zhihe\PrimaryPosts\Event\PrimaryPostUnmarked;

class UnmarkPrimaryController extends AbstractDeleteController
{
    protected $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    protected function delete(ServerRequestInterface $request)
    {
        $actor = RequestUtil::getActor($request);
        $postId = Arr::get($request->getQueryParams(), 'id');

        return $this->db->transaction(function () use ($actor, $postId) {
            $post = Post::findOrFail($postId);
            
            // Only discussion author can unmark posts
            if ($actor->id !== $post->discussion->user_id) {
                throw new ValidationException(['error' => 'Only discussion author can unmark posts']);
            }

            // Allow unmarking any post (removed restriction on first post)

            // Skip if not marked as primary
            if (!$post->is_primary) {
                return;
            }

            $post->is_primary = false;
            $post->primary_number = null;
            $post->save();

            // Emit event for extensibility
            event(new PrimaryPostUnmarked($post));
        });
    }
}