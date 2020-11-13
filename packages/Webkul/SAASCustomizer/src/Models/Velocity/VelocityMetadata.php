<?php

namespace Webkul\SAASCustomizer\Models\Velocity;

use Webkul\Velocity\Models\VelocityMetadata as BaseModel;

use Company;

class VelocityMetadata extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'locale',
        'channel',
        'home_page_content',
        'footer_left_content',
        'footer_middle_content',
        'slider',
        'subscription_bar_content',
        'product_policy',
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
            return new \Illuminate\Database\Eloquent\Builder($query->where('velocity_meta_data' . '.company_id', $company->id));
        }
    }
}