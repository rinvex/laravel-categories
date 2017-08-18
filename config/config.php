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
        'category' => \Cortex\Categorizable\Models\Category::class,
    ],

];
