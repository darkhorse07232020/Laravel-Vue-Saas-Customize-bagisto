<?php

namespace Webkul\SAASCustomizer\Observers\Product;

use Webkul\SAASCustomizer\Models\Product\ProductDownloadableLink;

use Company;

class ProductDownloadableLinkObserver
{
    public function creating(ProductDownloadableLink $model)
    {
        if(! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }

    // public function updating(ProductDownloadableLink $model)
    // {
    //     if(! auth()->guard('super-admin')->check()) {
    //         $model->company_id = Company::getCurrent()->id;
    //     }
    // }
}