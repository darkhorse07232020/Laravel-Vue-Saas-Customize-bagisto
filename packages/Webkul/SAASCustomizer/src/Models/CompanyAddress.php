<?php

namespace Webkul\SAASCustomizer\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\Product;
use Webkul\Core\Models\Locale;

class CompanyAddress extends Model
{
    protected $table = 'company_addresses';

    protected $fillable = ['address1', 'address2', 'city', 'state', 'zip_code', 'country', 'phone', 'misc'];
}