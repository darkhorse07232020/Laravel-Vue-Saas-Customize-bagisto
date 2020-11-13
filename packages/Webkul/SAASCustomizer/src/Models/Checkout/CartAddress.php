<?php

namespace Webkul\SAASCustomizer\Models\Checkout;

use Webkul\Checkout\Models\CartAddress as BaseModel;

use Company;

class CartAddress extends BaseModel
{
    protected $fillable = [
        'customer_id',
        'address_type',
        'cart_id',
        'company_name',
        'address1',
        'country',
        'state',
        'city',
        'postcode',
        'phone',
        'default_address',
        'first_name',
        'last_name',
        'email',
        'company_id',
    ];

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
            return new \Illuminate\Database\Eloquent\Builder($query->where('addresses' . '.company_id', $company->id));
        }
    }
}