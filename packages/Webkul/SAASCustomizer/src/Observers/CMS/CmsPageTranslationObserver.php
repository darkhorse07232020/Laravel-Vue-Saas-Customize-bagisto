<?php

namespace Webkul\SAASCustomizer\Observers\CMS;

use Webkul\SAASCustomizer\Models\CMS\CmsPageTranslation;

use Company;

class CmsPageTranslationObserver
{
    public function creating(CmsPageTranslation $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}