<?php

declare(strict_types=1);

return [

    // Manage autoload migrations
    'autoload_migrations' => true,

    // Categories Database Tables
    'tables' => [

        'categories' => 'categories',
        'categorizables' => 'categorizables',

    ],

    // Categories Models
    'models' => [
        'category' => \Rinvex\Categories\Models\Category::class,
    ],

];
