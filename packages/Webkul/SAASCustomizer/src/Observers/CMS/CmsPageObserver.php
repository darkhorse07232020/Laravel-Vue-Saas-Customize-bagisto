<?php

namespace Webkul\SAASCustomizer\Observers\CMS;

use Webkul\SAASCustomizer\Models\CMS\CmsPage;

use Company;

class CmsPageObserver
{
    public function creating(CmsPage $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}