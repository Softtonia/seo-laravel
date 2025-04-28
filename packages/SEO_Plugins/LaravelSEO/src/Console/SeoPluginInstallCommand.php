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


// namespace SEO_Plugins\LaravelSEO\Console;

// use Illuminate\Console\Command;
// use Illuminate\Support\Facades\Artisan;
// use Illuminate\Support\Facades\File;
// use Illuminate\Support\Facades\Schema;
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

//         // $this->call('seo:seo-generate-meta');
//         // $this->info(Artisan::output());

//         // Check if the 'roles' table exists
//         if (Schema::hasTable('roles')) {
//             // Create roles if the table exists
//             $this->registerDefaultRoles();
//         } else {
//             // Warning message if the 'roles' table doesn't exist
//             $this->warn('Roles table does not exist. Please ensure that the Spatie/Permission package is installed and migrations have been run.');
//         }

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
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Composer\InstalledVersions;

class SeoPluginInstallCommand extends Command
{
    protected $signature = 'seo:seo-plugin-install';
    protected $description = 'Install the Laravel SEO plugin (publish and migrate)';

    public function handle()
    {
        $this->info('🚀 Starting Laravel SEO Plugin Installation...');

        // Step 1: Check if Spatie Laravel Permission is properly installed
        if (!$this->isSpatiePermissionInstalled()) {
            $this->showSpatieInstallInstructions();
            return;
        }

        // Step 2: Check if Sanctum or Passport is installed
        $sanctumInstalled = $this->isSanctumInstalled();
        $passportInstalled = $this->isPassportInstalled();

        if (!$sanctumInstalled && !$passportInstalled) {
            $this->showAuthInstallInstructions();
            return;
        }

        // Show which package is being used
        if ($sanctumInstalled) {
            $this->info('✅ Laravel Sanctum is installed and will be used for API authentication.');
        }
        if ($passportInstalled) {
            $this->info('✅ Laravel Passport is installed and will be used for API authentication.');
        }

        // Proceed with installation
        $this->publishAssets();
        $this->runSeoMigrations(); // Changed from runMigrations()
        $this->registerDefaultRoles();

        $this->info('✅ SEO Plugin installed successfully!');
        $this->info('👉 You may now configure your SEO settings in config/seo.php');
    }

    protected function isSpatiePermissionInstalled(): bool
    {
        return class_exists(\Spatie\Permission\PermissionServiceProvider::class) &&
               class_exists(\Spatie\Permission\Models\Role::class) &&
               InstalledVersions::isInstalled('spatie/laravel-permission');
    }

    protected function isSanctumInstalled(): bool
    {
        return class_exists(\Laravel\Sanctum\SanctumServiceProvider::class) &&
               class_exists(\Laravel\Sanctum\Sanctum::class) &&
               InstalledVersions::isInstalled('laravel/sanctum');
    }

    protected function isPassportInstalled(): bool
    {
        return class_exists(\Laravel\Passport\PassportServiceProvider::class) &&
               class_exists(\Laravel\Passport\Passport::class) &&
               InstalledVersions::isInstalled('laravel/passport');
    }

    protected function showSpatieInstallInstructions()
    {
        $this->error('❌ Spatie Laravel-Permission package is not installed properly.');
        $this->warn('👉 Please install it by running:');
        $this->line('composer require spatie/laravel-permission');
        $this->line('php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"');
        $this->line('php artisan migrate');
        $this->newLine();
        $this->line('After installation, run this command again.');
    }

    protected function showAuthInstallInstructions()
    {
        $this->error('❌ Neither Laravel Sanctum nor Laravel Passport is installed.');
        $this->warn('👉 You must install one of them for API authentication:');

        $this->info('Option 1: Sanctum (recommended for simple APIs)');
        $this->line('composer require laravel/sanctum');
        $this->line('php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"');
        $this->line('php artisan migrate');

        $this->newLine();

        $this->info('Option 2: Passport (recommended for OAuth2 APIs)');
        $this->line('composer require laravel/passport');
        $this->line('php artisan migrate');
        $this->line('php artisan passport:install');

        $this->newLine();
        $this->line('After installation, run this command again.');
    }

    protected function publishAssets()
    {
        $this->info('📦 Publishing config, migrations, and assets...');

        Artisan::call('vendor:publish', [
            '--provider' => "SEO_Plugins\\LaravelSEO\\SeoServiceProvider",
            '--force' => true,
        ]);

        $this->info(Artisan::output());
    }

    protected function runSeoMigrations()
    {
        $this->info('🛠 Running SEO plugin migrations...');

        // Get the path to your package's migrations
        $migrationPath = dirname(__DIR__, 2) . '/database/migrations';

        try {
            Artisan::call('migrate', [
                '--path' => $migrationPath,
                '--force' => true,
            ]);
            $this->info(Artisan::output());
        } catch (\Exception $e) {
            $this->error('❌ Error running migrations: ' . $e->getMessage());
            $this->warn('👉 If you have existing tables, try running:');
            $this->line('php artisan migrate:fresh --seeder=YourSeeder');
        }
    }

    protected function registerDefaultRoles()
    {
        if (!Schema::hasTable('roles')) {
            $this->warn('⚠️ Roles table does not exist. Running Spatie Permission migrations...');
            Artisan::call('migrate', [
                '--path' => 'vendor/spatie/laravel-permission/database/migrations',
                '--force' => true,
            ]);
            $this->info(Artisan::output());
        }

        $roles = ['Super Admin', 'SEO Manager', 'Shop Manager'];

        foreach ($roles as $role) {
            if (!Role::where('name', $role)->exists()) {
                Role::create(['name' => $role]);
                $this->info("Created role: {$role}");
            }
        }

        $this->info('✅ Default SEO Roles have been registered: ' . implode(', ', $roles));
    }
}
