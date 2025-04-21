<?php
namespace SEO_Plugins\LaravelSEO\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SeoPluginInstallCommand extends Command
{
    protected $signature = 'seo:seo-plugin-install';
    protected $description = 'Install the Laravel SEO plugin (publish and migrate)';

    public function handle()
    {
        $this->info('Publishing config, migrations, and assets...');

        Artisan::call('vendor:publish', [
            '--provider' => "SEO_Plugins\\LaravelSEO\\SeoServiceProvider",
            '--force' => true,
        ]);

        $this->info(Artisan::output());

        $this->info('Running migrations...');
        Artisan::call('migrate');
        $this->info(Artisan::output());

        $this->info('✅ SEO Plugin installed successfully.');
    }
}
