<?php

namespace Webkul\SAASCustomizer\Models\Product;

use Webkul\Product\Models\ProductDownloadableLink as BaseModel;

use Company;

class ProductDownloadableLink extends BaseModel
{
    protected $fillable = ['title', 'price', 'url', 'file', 'file_name', 'type', 'sample_url', 'sample_file', 'sample_file_name', 'sample_type', 'sort_order', 'product_id', 'downloads', 'company_id'];

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
            return new \Illuminate\Database\Eloquent\Builder($query->where('product_downloadable_links' . '.company_id', $company->id));
        }
    }
}