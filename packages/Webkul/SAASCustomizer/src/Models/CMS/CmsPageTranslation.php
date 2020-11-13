<?php

namespace Webkul\SAASCustomizer\Models\CMS;

use Webkul\CMS\Models\CmsPageTranslation as BaseModel;

use Company;

class CmsPageTranslation extends BaseModel
{
    protected $fillable = ['page_title', 'url_key', 'html_content', 'meta_title', 'meta_description', 'meta_keywords', 'locale', 'cms_page_id', 'company_id'];

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
            return new \Illuminate\Database\Eloquent\Builder($query->where('cms_page_translations' . '.company_id', $company->id));
        }
    }
}