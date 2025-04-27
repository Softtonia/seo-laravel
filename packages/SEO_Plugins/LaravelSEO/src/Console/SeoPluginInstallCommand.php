<?php
// namespace SEO_Plugins\LaravelSEO\Console;

// use Illuminate\Console\Command;
// use Illuminate\Support\Facades\Artisan;
// use Illuminate\Support\Facades\File;
// use Spatie\Permission\Models\Role;

// class SeoPluginInstallCommand extends Command
// {
//     protected $signature = 'seo:seo-plugin-install';
//     protected $description = 'Install the Laravel SEO plugin (publish and migrate)';

//     public function handle()
//     {
//         $this->info('Publishing config, migrations, and assets...');

//         Artisan::call('vendor:publish', [
//             '--provider' => "SEO_Plugins\\LaravelSEO\\SeoServiceProvider",
//             '--force' => true,
//         ]);

//         $this->info(Artisan::output());

//         $this->info('Running migrations...');
//         Artisan::call('migrate');
//         $this->info(Artisan::output());

//         $this->call('seo:seo-generate-meta');
//         $this->info(Artisan::output());

//         // Create roles
//         $this->registerDefaultRoles();

//         $this->info('✅ SEO Plugin installed successfully.');
//     }


//     protected function registerDefaultRoles()
//     {
//         $roles = ['Super Admin', 'SEO Manager', 'Shop Manager'];

//         foreach ($roles as $role) {
//             Role::firstOrCreate(['name' => $role]);
//         }

//         $this->info('✅ Default SEO Roles have been registered.');
//     }
// }


namespace SEO_Plugins\LaravelSEO\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

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

        // $this->call('seo:seo-generate-meta');
        // $this->info(Artisan::output());

        // Check if the 'roles' table exists
        if (Schema::hasTable('roles')) {
            // Create roles if the table exists
            $this->registerDefaultRoles();
        } else {
            // Warning message if the 'roles' table doesn't exist
            $this->warn('Roles table does not exist. Please ensure that the Spatie/Permission package is installed and migrations have been run.');
        }

        $this->info('✅ SEO Plugin installed successfully.');
    }

    protected function registerDefaultRoles()
    {
        $roles = ['Super Admin', 'SEO Manager', 'Shop Manager'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $this->info('✅ Default SEO Roles have been registered.');
    }
}


