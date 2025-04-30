<?php

namespace SEO_Plugins\LaravelSEO\Observers;


use Illuminate\Support\Facades\Auth;
use SEO_Plugins\LaravelSEO\Models\SeoAuditLog;

class SeoAuditObserver
{
    public function created($model)
    {
        $this->log('created', $model);
    }

    public function updated($model)
    {
        $this->log('updated', $model, $model->getChanges());
    }

    public function deleted($model)
    {
        $this->log('deleted', $model);
    }

    protected function log($action, $model, $details = null)
    {
        SeoAuditLog::create([
            'user_id'           => Auth::id(),
            'action'            => $action,
            'target_model_type' => get_class($model),
            'target_model_id'   => $model->id,
            'details'           => $details ? json_encode($details) : json_encode($model->attributesToArray()),
            'created_at'        => now(),
        ]);
    }
}
