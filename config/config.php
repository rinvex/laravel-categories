<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Category Database Tables
    |--------------------------------------------------------------------------
    */

    'tables' => [

        /*
        |--------------------------------------------------------------------------
        | Categories Table
        |--------------------------------------------------------------------------
        |
        | Specify database table name that should be used to store
        | your categories. You may use whatever you like.
        |
        | Default: "categories"
        |
        */

        'categories' => 'categories',

        /*
        |--------------------------------------------------------------------------
        | Categorizables Table
        |--------------------------------------------------------------------------
        |
        | Specify database table name that should be used to store the relation
        | between "categories" and "entities". You may use whatever you like.
        |
        | Default: "categorizables"
        |
        */

        'categorizables' => 'categorizables',

    ],

];
