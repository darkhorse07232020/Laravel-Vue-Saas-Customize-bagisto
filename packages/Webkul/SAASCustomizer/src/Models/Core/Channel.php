<?php

namespace Webkul\SAASCustomizer\Models\Core;

use Webkul\Core\Models\Channel as BaseModel;

use Company;

class Channel extends BaseModel
{
    protected $fillable = ['code', 'name', 'description', 'theme', 'home_page_content', 'footer_content', 'hostname', 'default_locale_id', 'base_currency_id', 'root_category_id', 'home_seo'];

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
            return new \Illuminate\Database\Eloquent\Builder($query->where('channels' . '.company_id', $company->id));
        }
    }
}