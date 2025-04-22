<?php

namespace SEO_Plugins\LaravelSEO\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SeoPluginUpdateCommand extends Command
{
    protected $signature = 'seo:seo-plugin-update';
    protected $description = 'Update the Laravel SEO plugin (republish config, assets, and run migrations)';

    public function handle()
    {
        $this->info('Updating SEO plugin...');

        $configPath = config_path('seo.php');
        $backupPath = config_path('seo.php.bak');

        // Backup existing config file
        if (File::exists($configPath)) {
            File::copy($configPath, $backupPath);
            $this->info("🔒 Backup created at: {$backupPath}");
        }

        // Re-publish config file (with confirmation)
        if ($this->confirm('Do you want to overwrite the existing seo.php config file?', false)) {
            $this->call('vendor:publish', [
                '--provider' => "SEO_Plugins\\LaravelSEO\\SeoServiceProvider",
                '--tag' => 'config',
                '--force' => true,
            ]);
        }

        // Publish assets
        $this->call('vendor:publish', [
            '--provider' => "SEO_Plugins\\LaravelSEO\\SeoServiceProvider",
            '--tag' => 'assets',
            '--force' => true,
        ]);

        // Run migrations
        $this->call('migrate');

        $this->info('✅ SEO Plugin updated successfully.');
    }
}
