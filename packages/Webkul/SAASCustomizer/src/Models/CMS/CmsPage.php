<?php

namespace Webkul\SAASCustomizer\Models\CMS;

use Webkul\CMS\Models\CmsPage as BaseModel;

use Company;

class CmsPage extends BaseModel
{
    protected $fillable = ['layout', 'company_id'];
    
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
            return new \Illuminate\Database\Eloquent\Builder($query->where('cms_pages' . '.company_id', $company->id));
        }
    }
}