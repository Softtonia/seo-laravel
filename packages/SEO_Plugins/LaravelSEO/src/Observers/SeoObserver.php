<?php
// namespace SEO_Plugins\LaravelSEO\Observers;

// use SEO_Plugins\LaravelSEO\Models\SeoMeta;
// use Illuminate\Support\Str;

// class SeoObserver
// {
//     public function created($model)
//     {
//         if (isset($model->title) || isset($model->name)) {
//             SeoMeta::create([
//                 'model_type' => get_class($model),
//                 'model_id' => $model->id,
//                 'route_name' => request()->route() ? request()->route()->getName() : null,

//                 // 👇 Use correct column names
//                 'meta_title' => $model->title ?? null,
//                 'meta_description' => $model->excerpt ?? $model->description ?? null,
//                 'meta_keywords' => null, // Or generate dynamically if needed

//                 // Optional: If your model has og/twitter info
//                 'og_title' => $model->title ?? null,
//                 'og_description' => $model->excerpt ?? null,
//                 'og_image' => null,

//                 'twitter_title' => $model->title ?? null,
//                 'twitter_description' => $model->excerpt ?? null,
//                 'twitter_image' => null,
//             ]);

//         }
//     }
// }


namespace SEO_Plugins\LaravelSEO\Observers;

use SEO_Plugins\LaravelSEO\Models\SeoMeta;
use SEO_Plugins\LaravelSEO\Models\SeoSchemaMarkup;
use Illuminate\Support\Str;

class SeoObserver
{
    public function created($model)
    {
        $this->createOrUpdateSeoMeta($model);
        $this->createOrUpdateSchemaMarkup($model);
    }

    public function updated($model)
    {
        $this->createOrUpdateSeoMeta($model);
        $this->createOrUpdateSchemaMarkup($model);
    }

    protected function createOrUpdateSeoMeta($model)
    {
        if (!isset($model->title) && !isset($model->name)) {
            return;
        }

        // $routeName = request()->route() ? request()->route()->getName() : null;
        // Use the model's slug directly for route_name
        $routeName = $model->slug ?? Str::slug($model->title ?? $model->name);
        $metaTitle = $model->title ?? $model->name ?? null;
        $metaDescription = $model->excerpt ?? $model->description ?? Str::limit(strip_tags($model->body ?? $model->content ?? ''), 160);

        // Canonical URL based on domain + slug
        $slug = $model->slug ?? Str::slug($metaTitle);
        $domain = config('app.url');
        $canonicalUrl = $domain . '/' . $slug;

        // 🔹 Dynamic keyword generator
        $keywords = collect([
            $model->category->name ?? null, // If model has category
            $metaTitle,
            'SEO',
            'Laravel',
        ])
            ->filter()
            ->unique()
            ->implode(', ');

        $data = [
            'route_name'          => $routeName,
            'model_type'          => class_basename($model),
            'model_id'            => $model->id,
            'meta_title'          => $metaTitle,
            'meta_description'    => $metaDescription,
            'meta_keywords'       => $keywords,
            'canonical_url'       => $canonicalUrl,
            'og_title'            => $metaTitle,
            'og_description'      => $metaDescription,
            'og_image'            => method_exists($model, 'getOgImage') ? $model->getOgImage() : null,
            'twitter_title'       => $metaTitle,
            'twitter_description' => $metaDescription,
            'twitter_image'       => method_exists($model, 'getTwitterImage') ? $model->getTwitterImage() : null,
        ];

        SeoMeta::updateOrCreate([
            'model_type' => class_basename($model),
            'model_id'   => $model->id,
        ], $data);
    }

    protected function createOrUpdateSchemaMarkup($model)
    {
        $title = $model->title ?? $model->name ?? 'WebPage';
        $slug = $model->slug ?? Str::slug($title);
        $canonicalUrl = config('app.url') . '/' . $slug;

        $schemaData = [
            "@context" => "https://schema.org",
            "@type"    => "WebPage",
            "name"     => $title,
            "url"      => $canonicalUrl,
        ];

        SeoSchemaMarkup::updateOrCreate([
            'model_type' => class_basename($model),
            'model_id'   => $model->id,
        ], [
            'schema_type'  => 'WebPage',
            'schema_json'  => json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'page_url'    => $canonicalUrl,
        ]);
    }
}

