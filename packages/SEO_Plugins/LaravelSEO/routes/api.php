<?php

use SEO_Plugins\LaravelSEO\Http\Controllers\SeoController;
use SEO_Plugins\LaravelSEO\Http\Controllers\SeoRobotController;
use SEO_Plugins\LaravelSEO\Http\Controllers\SeoSchemaMarkupController;
use SEO_Plugins\LaravelSEO\Http\Controllers\SeoSettingController;
use SEO_Plugins\LaravelSEO\Http\Controllers\SeoSitemapController;
use SEO_Plugins\LaravelSEO\Models\SeoRobot;




Route::prefix('api')->group(function () {

    Route::prefix('/seo-meta')->group(function(){
        Route::get('/', [SeoController::class, 'index']);
        Route::post('/', [SeoController::class, 'store']);
        Route::get('/{id}', [SeoController::class, 'show']);
        Route::post('/{id}', [SeoController::class, 'update']);
        Route::delete('/{id}', [SeoController::class, 'destroy']);
    });


    Route::prefix('/seo-sitemaps')->group(function(){
        Route::controller(SeoSitemapController::class)->group(function(){
            ROute::get('/','index');
            Route::get('/{id}', 'show');
            Route::post('/', 'store');
            Route::post('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
    });

    Route::prefix('/seo-robots')->group(function(){
        Route::controller(SeoRobotController::class)->group(function(){
            Route::get('/','index');
            Route::get('/{id}', 'show');
            Route::post('/', 'store');
            Route::post('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
    });


    Route::prefix('/seo-schema-markup')->group(function(){
        Route::controller(SeoSchemaMarkupController::class)->group(function(){
            Route::get('/','index');
            Route::get('/{id}', 'show');
            Route::post('/', 'store');
            Route::post('/{id}', 'update');
            Route::delete('/{id}', 'destroy');

        });
    });


    Route::prefix('/seo-settings')->group(function(){
        Route::controller(SeoSettingController::class)->group(function(){
            Route::get('/','index');
            Route::get('/{id}', 'show');
            Route::post('/', 'store');
            Route::post('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
    });

});

