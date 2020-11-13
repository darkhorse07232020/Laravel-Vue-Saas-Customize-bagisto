<?php

namespace Webkul\SAASCustomizer\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\SAASCustomizer\Contracts\SuperConfig as SuperConfigContract;

class SuperConfig extends Model implements SuperConfigContract
{
    protected $table = 'super_config';

    protected $fillable = [
        'code', 'value', 'channel_code', 'locale_code'
    ];

    protected $hidden = ['token'];
}
