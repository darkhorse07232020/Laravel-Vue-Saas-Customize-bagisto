<?php

namespace Webkul\SAASCustomizer\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\SAASCustomizer\Models\Company::class,
        \Webkul\SAASCustomizer\Models\CompanyDetails::class,
        \Webkul\SAASCustomizer\Models\Agent::class,
        \Webkul\SAASCustomizer\Models\Locale::class,
        \Webkul\SAASCustomizer\Models\Currency::class,
        \Webkul\SAASCustomizer\Models\CurrencyExchangeRate::class,
        \Webkul\SAASCustomizer\Models\Channel::class,
        \Webkul\SAASCustomizer\Models\SuperConfig::class
    ];
}