<?php

namespace Webkul\SAASCustomizer\Http\Middleware;

use Webkul\SAASCustomizer\Repositories\Super\LocaleRepository;
use Closure;

class CompanyLocale
{
    /**
     * @var LocaleRepository
     */
    protected $locale;

    /**
     * @param \Webkul\SAASCustomizer\Repositories\LocaleRepository $locale
     */
    public function __construct(LocaleRepository $locale)
    {
        $this->locale = $locale;
    }

    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        $locale = request()->get('company-locale');

        if ($locale) {
            if ($this->locale->findOneByField('code', $locale)) {
                app()->setLocale($locale);

                session()->put('company-locale', $locale);
            }
        } else {
            if ($locale = session()->get('company-locale')) {
                app()->setLocale($locale);
            } else {
                if ( company()->getDefaultChannel() ) {
                    app()->setLocale(company()->getDefaultChannel()->default_locale->code);
                } else {
                    app()->setLocale(app()->getLocale());
                }
                
            }
        }

        return $next($request);
    }
}
