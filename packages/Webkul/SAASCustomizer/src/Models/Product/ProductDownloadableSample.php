<?php

namespace Webkul\SAASCustomizer\Models\Product;

use Webkul\Product\Models\ProductDownloadableSample as BaseModel;

use Company;

class ProductDownloadableSample extends BaseModel
{
    protected $fillable = ['url', 'file', 'file_name', 'type', 'sort_order', 'product_id', 'company_id'];

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
            return new \Illuminate\Database\Eloquent\Builder($query->where('product_downloadable_samples' . '.company_id', $company->id));
        }
    }

}