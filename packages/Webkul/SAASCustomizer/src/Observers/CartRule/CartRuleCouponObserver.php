<?php

namespace Webkul\SAASCustomizer\Observers\CartRule;

use Webkul\SAASCustomizer\Models\CartRule\CartRuleCoupon;

use Company;

class CartRuleCouponObserver
{
    public function creating(CartRuleCoupon $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}