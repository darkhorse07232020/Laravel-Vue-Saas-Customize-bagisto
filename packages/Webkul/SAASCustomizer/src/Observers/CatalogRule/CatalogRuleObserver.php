<?php

namespace Webkul\SAASCustomizer\Observers\CatalogRule;

use Webkul\SAASCustomizer\Models\CatalogRule\CatalogRule;

use Company;

class CatalogRuleObserver
{
    public function creating(CatalogRule $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}