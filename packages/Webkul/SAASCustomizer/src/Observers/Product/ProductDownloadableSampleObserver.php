<?php

namespace Webkul\SAASCustomizer\Observers\Product;

use Webkul\SAASCustomizer\Models\Product\ProductDownloadableSample;

use Company;

class ProductDownloadableSampleObserver
{
    public function creating(ProductDownloadableSample $model)
    {
        if(! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}