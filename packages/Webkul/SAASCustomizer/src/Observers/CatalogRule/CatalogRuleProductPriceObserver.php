<?php

namespace Webkul\SAASCustomizer\Observers\CatalogRule;

use Webkul\SAASCustomizer\Models\CatalogRule\CatalogRuleProductPrice;

use Company;

class CatalogRuleProductPriceObserver
{
    public function creating(CatalogRuleProductPrice $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}