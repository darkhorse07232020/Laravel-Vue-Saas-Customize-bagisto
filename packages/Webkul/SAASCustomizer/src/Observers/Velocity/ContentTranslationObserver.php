<?php

namespace Webkul\SAASCustomizer\Observers\Velocity;

use Webkul\SAASCustomizer\Models\Velocity\ContentTranslation;

use Company;

class ContentTranslationObserver
{
    public function creating(ContentTranslation $model)
    {
        if (Company::getCurrent()) {
            if (! isset($model->company_id)) {
                $model->company_id = Company::getCurrent()->id;
            }
        }
    }
}