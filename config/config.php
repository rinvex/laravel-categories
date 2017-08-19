<?php

declare(strict_types=1);

return [

    // Categorizable Database Tables
    'tables' => [

        'categories' => 'categories',
        'categorizables' => 'categorizables',

    ],

    // Categorizable Models
    'models' => [
        'category' => \Rinvex\Categorizable\Models\Category::class,
    ],

];
