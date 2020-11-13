<?php

namespace Webkul\SAASCustomizer\Observers\Velocity;

use Webkul\SAASCustomizer\Models\Velocity\OrderBrand;

use Company;

class OrderBrandObserver
{
    public function creating(OrderBrand $model)
    {
        if (Company::getCurrent()) {
            if (! isset($model->company_id)) {
                $model->company_id = Company::getCurrent()->id;
            }
        }
    }
}