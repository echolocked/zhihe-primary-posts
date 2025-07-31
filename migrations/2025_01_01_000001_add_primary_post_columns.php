<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        if (!$schema->hasColumn('posts', 'is_primary')) {
            $schema->table('posts', function (Blueprint $table) {
                $table->boolean('is_primary')->default(false);
            });
        }
        
        if (!$schema->hasColumn('posts', 'primary_number')) {
            $schema->table('posts', function (Blueprint $table) {
                $table->integer('primary_number')->nullable();
            });
        }
        
        // Add index for performance
        $schema->table('posts', function (Blueprint $table) {
            $table->index(['discussion_id', 'is_primary', 'created_at'], 'posts_primary_idx');
        });
    },
    'down' => function (Builder $schema) {
        $schema->table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_primary_idx');
            $table->dropColumn(['is_primary', 'primary_number']);
        });
    }
];