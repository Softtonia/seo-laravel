<?php

namespace SEO_Plugins\LaravelSEO\Console;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\Page;
use SEO_Plugins\LaravelSEO\Observers\SeoObserver;

class GenerateSeoMetaCommand extends Command
{
    protected $signature = 'seo:seo-generate-meta';
    protected $description = 'Generate SEO meta and schema markup for existing posts and pages';

    public function handle()
    {
        $observer = new SeoObserver();

        $this->info("🔄 Generating SEO data for Posts...");
        Post::chunk(50, function ($posts) use ($observer) {
            foreach ($posts as $post) {
                $observer->created($post); // reuse observer logic
            }
        });

        $this->info("🔄 Generating SEO data for Pages...");
        Page::chunk(50, function ($pages) use ($observer) {
            foreach ($pages as $page) {
                $observer->created($page);
            }
        });

        $this->info("✅ SEO data generated successfully!");
    }
}
