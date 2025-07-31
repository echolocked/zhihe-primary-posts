<?php

namespace Zhihe\PrimaryPosts\Migration;

use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

return Migration::addColumns('posts', [
    'is_primary' => ['boolean', 'default' => false],
    'primary_number' => ['integer', 'nullable' => true],
]);