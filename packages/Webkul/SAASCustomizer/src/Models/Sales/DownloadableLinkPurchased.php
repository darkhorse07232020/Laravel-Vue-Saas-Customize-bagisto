<?php

namespace Webkul\SAASCustomizer\Models\Sales;

use Webkul\Sales\Models\DownloadableLinkPurchased as BaseModel;

use Company;

class DownloadableLinkPurchased extends BaseModel
{
    protected $table = 'downloadable_link_purchased';

    protected $fillable = ['product_name', 'name', 'url', 'file', 'file_name', 'type', 'download_bought', 'download_used', 'status', 'customer_id', 'order_id', 'order_item_id', 'company_id'];
    
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
            return new \Illuminate\Database\Eloquent\Builder($query->where('downloadable_link_purchased' . '.company_id', $company->id));
        }
    }
}