<?php

namespace Webkul\SAASCustomizer\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\SAASCustomizer\Contracts\Locale as LocaleContract;

class Locale extends Model implements LocaleContract
{
    protected $table = 'super_locales';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'direction'
    ];
}
