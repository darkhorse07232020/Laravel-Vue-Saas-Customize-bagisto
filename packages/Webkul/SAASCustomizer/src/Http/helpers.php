<?php
    use Webkul\SAASCustomizer\Company;

    if (! function_exists('company')) {
        function company()
        {
            return app()->make(Company::class);
        }
    }