<?php

namespace SEO_Plugins\LaravelSEO\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSitemap extends Model
{
    protected $table = 'seo_sitemaps';
    protected $guarded = [];

    protected $casts = [
        'last_modified' => 'datetime',
    ];
}
