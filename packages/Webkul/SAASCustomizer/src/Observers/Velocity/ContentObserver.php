<?php

namespace Webkul\SAASCustomizer\Observers\Velocity;

use Webkul\SAASCustomizer\Models\Velocity\Content;

use Company;

class ContentObserver
{
    public function creating(Content $model)
    {
        if (Company::getCurrent()) {
            if (! isset($model->company_id)) {
                $model->company_id = Company::getCurrent()->id;
            }
        }
    }
}