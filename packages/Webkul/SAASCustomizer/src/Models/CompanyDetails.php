<?php

namespace Webkul\SAASCustomizer\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\SAASCustomizer\Contracts\CompanyDetails as CompanyDetailsContract;

class CompanyDetails extends Model implements CompanyDetailsContract
{
    protected $table = 'company_personal_details';

    protected $fillable = ['first_name', 'last_name', 'email', 'skype', 'extra_info', 'company_id', 'phone', 'channel_id'];
}