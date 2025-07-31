<?php

namespace Zhihe\PrimaryPosts\Api\Controller;

use Flarum\Api\Controller\AbstractCreateController;
use Flarum\Http\RequestUtil;
use Flarum\Post\Post;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Flarum\Foundation\ErrorHandling\ExceptionHandler\IlluminateValidationExceptionHandler;
use Illuminate\Validation\ValidationException;
use Zhihe\PrimaryPosts\Event\PrimaryPostMarked;

class MarkPrimaryController extends AbstractCreateController
{
    public $serializer = \Flarum\Api\Serializer\PostSerializer::class;

    protected $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);
        $postId = Arr::get($request->getQueryParams(), 'id');

        return $this->db->transaction(function () use ($actor, $postId) {
            $post = Post::findOrFail($postId);
            
            // Only discussion author can mark posts as primary
            if ($actor->id !== $post->discussion->user_id) {
                throw new ValidationException(['error' => 'Only discussion author can mark posts as primary']);
            }

            // Skip if already marked as primary
            if ($post->is_primary) {
                return $post;
            }

            // Calculate next primary number
            $maxNumber = Post::where('discussion_id', $post->discussion_id)
                           ->where('is_primary', true)
                           ->max('primary_number') ?? 0;

            $post->is_primary = true;
            $post->primary_number = $maxNumber + 1;
            $post->save();

            // Emit event for extensibility
            event(new PrimaryPostMarked($post));

            return $post;
        });
    }
}