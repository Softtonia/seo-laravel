<?php

namespace SEO_Plugins\LaravelSEO\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class SeoPluginUninstallCommand extends Command
{
    protected $signature = 'seo:seo-plugin-uninstall';
    protected $description = 'Uninstall the Laravel SEO plugin';

    public function handle()
    {
        // Step 1: Remove config file
        $configPath = config_path('seo.php');
        if (File::exists($configPath)) {
            File::delete($configPath);
            $this->info('Config file seo.php deleted.');
        }

        // Step 2: Remove assets from the public folder
        $publicAssets = public_path('vendor/seo');
        if (File::exists($publicAssets)) {
            File::deleteDirectory($publicAssets);
            $this->info('Public assets deleted from vendor/seo.');
        }

        // Step 3: Remove service provider and facade from config/app.php
        $this->removeServiceProvider('SEO_Plugins\\LaravelSEO\\SeoServiceProvider');
        $this->removeFacade('SEO');

        // Step 4: Optionally rollback migrations if needed
        if ($this->confirm('Do you want to rollback the plugin migrations?', true)) {
            $this->call('migrate:rollback', [
                '--path' => 'packages/SEO_Plugins/LaravelSEO/database/migrations',
            ]);
        }

        // Step 5: Remove the package using Composer
        $this->info('Removing the package from composer...');
        $this->removePackage();

        // Step 6: Remove the 'packages' folder if empty
        $this->removePackagesFolder();

        // Step 7: Clear the autoloader and cache
        $this->info('Clearing Composer cache and autoload...');
        Artisan::call('optimize:clear');
        Artisan::call('composer dump-autoload');

        $this->info('SEO Plugin uninstalled and cleaned successfully.');
    }

    private function removeServiceProvider($serviceProvider)
    {
        $configFilePath = base_path('config/app.php');
        $content = File::get($configFilePath);

        // Remove service provider from config/app.php
        if (strpos($content, $serviceProvider) !== false) {
            $content = str_replace(
                "    {$serviceProvider},\n",
                '',
                $content
            );
            File::put($configFilePath, $content);
            $this->info("Service provider {$serviceProvider} removed from config/app.php");
        }
    }

    private function removeFacade($alias)
    {
        $configFilePath = base_path('config/app.php');
        $content = File::get($configFilePath);

        // Remove facade alias from config/app.php
        if (strpos($content, "'{$alias}'") !== false) {
            $content = str_replace(
                "    '{$alias}' => {$alias}::class,\n",
                '',
                $content
            );
            File::put($configFilePath, $content);
            $this->info("Facade alias {$alias} removed from config/app.php");
        }
    }

    private function removePackage()
    {
        // Remove the package from composer.json
        $packageName = 'seo-plugins/laravel-seo'; // Replace with actual package name

        // Remove the package via composer
        exec("composer remove {$packageName}");

        // Check if composer remove was successful
        $this->info("Package {$packageName} removed successfully.");
    }

    private function removePackagesFolder()
    {
        // Path to the packages folder in the root directory
        $packagesFolder = base_path('packages');

        // Check if the folder exists
        if (File::exists($packagesFolder)) {
            // Get all files and folders inside the directory
            $files = array_diff(scandir($packagesFolder), ['.', '..']);

            // If there are files or directories inside the 'packages' folder, delete them first
            foreach ($files as $file) {
                $filePath = $packagesFolder . DIRECTORY_SEPARATOR . $file;
                try {
                    if (File::isDirectory($filePath)) {
                        File::deleteDirectory($filePath);
                        $this->info("Subdirectory {$file} deleted.");
                    } else {
                        File::delete($filePath);
                        $this->info("File {$file} deleted.");
                    }
                } catch (\Exception $e) {
                    $this->error("Failed to delete {$file}: " . $e->getMessage());
                }
            }

            // After deleting all files/subdirectories, delete the 'packages' folder itself
            if (File::isDirectory($packagesFolder) && count(scandir($packagesFolder)) == 2) {
                File::deleteDirectory($packagesFolder);
                $this->info('The packages folder has been deleted from the project.');
            } else {
                $this->info('The packages folder is not empty or could not be deleted.');
            }
        } else {
            $this->info('The packages folder does not exist.');
        }
    }
}
