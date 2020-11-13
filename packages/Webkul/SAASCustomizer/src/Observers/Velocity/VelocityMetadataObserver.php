<?php

namespace Webkul\SAASCustomizer\Observers\Velocity;

use Webkul\SAASCustomizer\Models\Velocity\VelocityMetadata;

use Company;

class VelocityMetadataObserver
{
    public function creating(VelocityMetadata $model)
    {
        if (Company::getCurrent()) {
            if (! isset($model->company_id)) {
                $model->company_id = Company::getCurrent()->id;
            }
        }
    }
}