
# Laravel SEO Plugin

A modular and extendable SEO plugin for Laravel applications. This package provides a simple interface for setting SEO metadata, Open Graph tags, Twitter cards, and more—making your Laravel project search-engine-friendly.

---

## 📁 Directory Structure

```
packages/
└── SEO_Plugins/
    └── LaravelSEO/
        ├── config/
        ├── database/
        ├── resources/
        ├── src/
        ├── Console/
        └── composer.json
```

---

## 🚀 Installation Steps

### 1. Copy the Package

Copy the `SEO_Plugins/LaravelSEO` directory into the `packages/` folder located in the **root directory** of your Laravel project.

Your structure should look like:

```
your-laravel-project/
├── app/
├── config/
├── packages/
│   └── SEO_Plugins/
│       └── LaravelSEO/
├── composer.json
```

---

### 2. Modify `composer.json`

In your Laravel project’s `composer.json` file, add the following:

#### Under `"repositories"` key:
```json
"repositories": [
    {
        "type": "path",
        "url": "packages/SEO_Plugins/LaravelSEO"
    }
],
```

#### Under `"require"` key:
```json
"require": {
    "seo-plugins/laravel-seo": "@dev"
}
```

---

### 3. Run Composer Update

Install the package dependencies by running:

```bash
composer update
```

(Optional) You may also run:

```bash
composer dump-autoload
```

---

## 📦 Laravel Artisan Commands

Once the package is installed, you can manage it using custom Artisan commands.

### ✅ Install the SEO Plugin

```bash
php artisan seo:seo-plugin-install
```

This will:
- Publish the configuration file
- Run required migrations
- Publish assets (if applicable)
- Register service provider and alias

---

### ♻️ Update the SEO Plugin

```bash
php artisan seo:seo-plugin-update
```

This will:
- Publish updated files if available
- Re-run migrations (optional)
- Refresh assets and configurations

---

### ❌ Uninstall the SEO Plugin

```bash
php artisan seo:seo-plugin-uninstall
```

This will:
- Delete the configuration file
- Delete public assets
- Remove provider and alias from `config/app.php`
- Optionally rollback migrations
- Remove the package via Composer
- Delete the `packages/SEO_Plugins/LaravelSEO` directory
- Clear Composer cache and Laravel optimizations

---

## ⚙️ Configuration Example

`config/seo.php` file example:

```php
return [
    'default_title' => 'My Laravel Application',
    'default_description' => 'This is a Laravel-based application with SEO optimization.',
    'default_keywords' => ['laravel', 'seo', 'plugin'],
];
```

---

## 🛠 Usage Example

In a controller or view:

```php
SEO::setTitle('About Us');
SEO::setDescription('Learn more about our Laravel SEO plugin.');
SEO::setKeywords(['about', 'seo', 'laravel']);
```

---

## ✍️ Author

Md Faiyyaj Alam
Laravel Developer – Softtonia


---

## 🧾 License

This project is open-source and licensed under the [MIT License](LICENSE).

---

## 💡 Tip

To develop and test local packages, always run:

```bash
composer dump-autoload
```

after any structural or namespace changes.
