<?php

namespace Zhihe\PrimaryPosts\Listener;

use Flarum\Discussion\Event\Saving as DiscussionSaving;
use Flarum\Post\Event\Saving as PostSaving;

class ProcessPrimaryPostData
{
    public function handleDiscussion(DiscussionSaving $event)
    {
        $discussion = $event->discussion;
        $data = $event->data;
        
        // Get attributes from JSON:API structure
        $attributes = $data['attributes'] ?? [];
        $isPrimary = $attributes['isPrimary'] ?? false;
        
        if ($isPrimary && !$discussion->exists) {
            // For new discussions, mark the first post after creation
            $discussion->afterSave(function ($discussion) {
                $firstPost = $discussion->firstPost;
                if ($firstPost) {
                    $firstPost->is_primary = true;
                    $firstPost->primary_number = 1;
                    $firstPost->save();
                }
            });
        }
    }
    
    public function handlePost(PostSaving $event)
    {
        $post = $event->post;
        $data = $event->data;
        
        // Get attributes from JSON:API structure
        $attributes = $data['attributes'] ?? [];
        $isPrimary = $attributes['isPrimary'] ?? false;
        
        if ($isPrimary && !$post->exists) {
            // Calculate next primary number for this discussion
            $maxNumber = $post->discussion->posts()
                           ->where('is_primary', true)
                           ->max('primary_number') ?? 0;

            $post->is_primary = true;
            $post->primary_number = $maxNumber + 1;
        }
    }
}