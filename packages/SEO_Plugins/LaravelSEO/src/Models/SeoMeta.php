<?php

namespace SEO_Plugins\LaravelSEO\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';
    protected $guarded =[];


    public function seoable()
    {
        return $this->morphTo('seoable', 'model_type', 'model_id');
    }
}
