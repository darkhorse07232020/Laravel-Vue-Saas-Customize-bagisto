<?php

namespace Webkul\SAASCustomizer\Http\Middleware;

use Closure;

class Locale
{

    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        $locale = request()->get('super-locale');

        if ($locale) {
            app()->setLocale($locale);
            
            session()->put('super-locale', $locale);
        } else {
            if ($locale = session()->get('super-locale')) {
                app()->setLocale($locale);
            } else {
                app()->setLocale(app()->getLocale());
            }
        }

        return $next($request);
    }
}
