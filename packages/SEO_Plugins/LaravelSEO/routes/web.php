<?php

use Illuminate\Support\Facades\Route;
use SEO_Plugins\LaravelSEO\Facades\Seo;

Route::get('seo-test', function () {
    return Seo::generateTitle('Welcome to SEO!');
});
