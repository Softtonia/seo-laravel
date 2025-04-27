<?php
return [
'default_title' => env('APP_NAME'),
'default_description' => 'Default meta description.',
'default_image' => null,
'enable_open_graph' => true,
'enable_twitter_card' => true,

    // 🔥 List of models to automatically attach SEO
    'models' => [
        \App\Models\Post::class,
        \App\Models\Page::class,
        // Add more models here
    ],


    //  Roles allowed to access SEO plugin
    'access_roles' => ['Super Admin', 'SEO Manager'],

    //  Guard to use (web/api)
    'guard' => env('SEO_PLUGIN_GUARD', 'api'),
];
