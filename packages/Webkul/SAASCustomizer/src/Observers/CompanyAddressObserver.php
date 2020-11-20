<?php

namespace Webkul\SAASCustomizer\Observers;

use Webkul\SAASCustomizer\Models\CompanyAddress;

use Company;

class CompanyAddressObserver
{
    public function creating(CompanyAddress $model)
    {
        if (Company::getCurrent()) {
            if (! isset($model->company_id)) {
                $model->company_id = Company::getCurrent()->id;
            }
        }
    }
}