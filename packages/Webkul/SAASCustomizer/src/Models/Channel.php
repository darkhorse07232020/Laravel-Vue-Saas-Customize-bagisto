<?php

namespace Webkul\SAASCustomizer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Webkul\SAASCustomizer\Contracts\Channel as ChannelContract;

class Channel extends Model implements ChannelContract
{
    protected $table = 'super_channel';

    protected $fillable = ['code', 'name', 'home_page_content', 'footer_page_content', 'hostname', 'default_locale_id', 'base_currency_id', 'home_seo'];

    /**
     * Get the super channel locales.
     */
    public function locales()
    {
        return $this->belongsToMany(LocaleProxy::modelClass(), 'super_channel_locales', 'super_channel_id');
    }

    /**
     * Get the default locale
     */
    public function default_locale()
    {
        return $this->belongsTo(LocaleProxy::modelClass());
    }

    /**
     * Get the super channel currencies.
     */
    public function currencies()
    {
        return $this->belongsToMany(CurrencyProxy::modelClass(), 'super_channel_currencies', 'super_channel_id');
    }

    /**
     * Get the base currency
     */
    public function base_currency()
    {
        return $this->belongsTo(CurrencyProxy::modelClass());
    }

    /**
     * Get logo image url.
     */
    public function logo_url()
    {
        if (! $this->logo)
            return;

        return Storage::url($this->logo);
    }

    /**
     * Get logo image url.
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo_url();
    }

    /**
     * Get favicon image url.
     */
    public function favicon_url()
    {
        if (! $this->favicon)
            return;

        return Storage::url($this->favicon);
    }

    /**
     * Get favicon image url.
     */
    public function getFaviconUrlAttribute()
    {
        return $this->favicon_url();
    }
}