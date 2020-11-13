<?php

namespace Webkul\SAASCustomizer\Observers\CartRule;

use Webkul\SAASCustomizer\Models\CartRule\CartRule as CartRuleModel;

use Company;

class CartRuleObserver
{
    public function creating(CartRuleModel $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}