<?php

namespace Webkul\SAASCustomizer\Models\CartRule;

use Webkul\CartRule\Models\CartRuleCoupon as BaseModel;

use Company;

class CartRuleCoupon extends BaseModel
{
    protected $fillable = ['code', 'usage_limit', 'usage_per_customer', 'times_used', 'type', 'cart_rule_id', 'expired_at', 'is_primary', 'company_id'];

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        $company = Company::getCurrent();

        if (auth()->guard('super-admin')->check() || ! isset($company->id)) {
            return new \Illuminate\Database\Eloquent\Builder($query);
        } else {
            return new \Illuminate\Database\Eloquent\Builder($query->where('cart_rule_coupons' . '.company_id', $company->id));

        }
    }
}