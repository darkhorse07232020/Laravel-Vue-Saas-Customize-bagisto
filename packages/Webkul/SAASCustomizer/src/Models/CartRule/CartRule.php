<?php

namespace Webkul\SAASCustomizer\Models\CartRule;

use Webkul\CartRule\Models\CartRule as BaseModel;

use Company;

class CartRule extends BaseModel
{
    protected $fillable = ['name', 'description', 'starts_from', 'ends_till', 'status', 'coupon_type', 'use_auto_generation', 'usage_per_customer', 'uses_per_coupon', 'times_used', 'condition_type', 'conditions', 'actions', 'end_other_rules', 'uses_attribute_conditions', 'action_type', 'discount_amount', 'discount_quantity', 'discount_step', 'apply_to_shipping', 'free_shipping', 'sort_order', 'company_id'];

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
            return new \Illuminate\Database\Eloquent\Builder($query->where('cart_rules' . '.company_id', $company->id));
        }
    }
}