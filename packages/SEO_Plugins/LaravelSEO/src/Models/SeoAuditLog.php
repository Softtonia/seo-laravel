<?php

namespace SEO_Plugins\LaravelSEO\Models;

use Illuminate\Database\Eloquent\Model;

class SeoAuditLog extends Model
{
    protected $table = 'seo_audit_logs';
    protected $fillable = [
        'user_id',
        'action',
        'target_model_type',
        'target_model_id',
        'details'
    ];





}
