<?php

use Illuminate\Support\Facades\File;
use ReflectionClass;
use SEO_Plugins\LaravelSEO\Contracts\SeoModelContract;


function getSeoModels(): array
{
    $models = [];
    $modelPath = app_path('Models');
    $files = File::allFiles($modelPath);

    foreach ($files as $file) {
        $namespace = 'App\\Models\\' . str_replace(['/', '.php'], ['\\', ''], $file->getRelativePathname());

        if (class_exists($namespace)) {
            $reflection = new ReflectionClass($namespace);
            if ($reflection->isSubclassOf(\Illuminate\Database\Eloquent\Model::class) &&
                $reflection->implementsInterface(SeoModelContract::class)) {
                $models[] = class_basename($namespace);
            }
        }
    }

    return $models;
}
