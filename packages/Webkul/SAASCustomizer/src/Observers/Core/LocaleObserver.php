<?php

namespace Webkul\SAASCustomizer\Observers\Core;

use Webkul\SAASCustomizer\Models\Core\Locale;

use Company;

class LocaleObserver
{
    public function creating(Locale $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }

    public function deleting(Locale $model)
    {
        if ($model->count() == 1) {
            session()->flash('error', trans('saas::app.tenant.custom-errors.locale-delete'));
        }
    }
}