<?php

namespace Webkul\SAASCustomizer\Observers\Sales;

use Webkul\SAASCustomizer\Models\Sales\DownloadableLinkPurchased;

use Company;

class DownloadableLinkPurchasedObserver
{
    public function creating(DownloadableLinkPurchased $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}