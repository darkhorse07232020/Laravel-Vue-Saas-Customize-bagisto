<?php

Route::group(['middleware' => ['web', 'super-locale']], function () {
    
    Route::prefix('super')->group(function() {
        
        // Super user routes
        Route::get('login', 'Webkul\SAASCustomizer\Http\Controllers\Super\SessionController@index')->defaults('_config', [
            'view' => 'saas::super.agents.login'
        ])->name('super.session.index');

        Route::post('login', 'Webkul\SAASCustomizer\Http\Controllers\Super\SessionController@store')->defaults('_config', [
            'redirect' => 'super.tenants.index'
        ])->name('super.session.create');

        // Forget Password Routes
        Route::get('forget-password', 'Webkul\SAASCustomizer\Http\Controllers\Super\ForgetPasswordController@create')->defaults('_config', [
            'view' => 'saas::super.agents.forget-password.create'
        ])->name('super.forget-password.create');

        Route::post('/forget-password', 'Webkul\SAASCustomizer\Http\Controllers\Super\ForgetPasswordController@store')->name('super.forget-password.store');

        // Reset Password Routes
        Route::get('/reset-password/{token}', 'Webkul\SAASCustomizer\Http\Controllers\Super\ResetPasswordController@create')->defaults('_config', [
            'view' => 'saas::super.agents.reset-password.create'
        ])->name('super.reset-password.create');

        Route::post('/reset-password', 'Webkul\SAASCustomizer\Http\Controllers\Super\ResetPasswordController@store')->defaults('_config', [
            'redirect' => 'super.tenants.index'
        ])->name('super.reset-password.store');

        Route::group(['middleware' => ['super-admin']], function () {
            Route::get('logout', 'Webkul\SAASCustomizer\Http\Controllers\Super\SessionController@destroy')->name('super.session.destroy');


            // Super Configuration routes
            Route::get('configuration/{slug?}/{slug2?}', 'Webkul\SAASCustomizer\Http\Controllers\Super\ConfigurationController@index')->defaults('_config', [
                'view' => 'saas::super.configuration.index'
            ])->name('super.configuration.index');

            Route::post('configuration/{slug?}/{slug2?}', 'Webkul\SAASCustomizer\Http\Controllers\Super\ConfigurationController@store')->defaults('_config', [
                'redirect' => 'super.configuration.index'
            ])->name('super.configuration.index.store');

            Route::get('configuration/{slug?}/{slug2?}/{path}', 'Webkul\SAASCustomizer\Http\Controllers\Super\ConfigurationController@download')->defaults('_config', [
                'redirect' => 'super.configuration.index'
            ])->name('super.configuration.download');

            Route::prefix('companies')->group(function() {
                // Tenant Routes
                Route::get('/tenants', 'Webkul\SAASCustomizer\Http\Controllers\Super\TenantController@list')->defaults('_config', [
                    'view' => 'saas::super.tenants.index'
                ])->name('super.tenants.index');

                Route::get('/tenants/edit/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\TenantController@edit')->defaults('_config', [
                    'view' => 'saas::super.tenants.edit'
                ])->name('super.tenants.edit');

                Route::post('/tenants/edit/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\TenantController@update')->defaults('_config', [
                    'redirect' => 'super.tenants.index'
                ])->name('super.tenants.update');
                
                Route::get('/tenants/view/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\TenantController@showCompanyStats')->defaults('_config', [
                    'view' => 'saas::super.tenants.view'
                ])->name('super.tenants.show-stats');

                Route::post('/tenants/delete/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\TenantController@destroy')->defaults('_config', [
                    'redirect' => 'super.tenants.index'
                ])->name('super.tenants.delete');

                Route::post('/tenants/massdelete', 'Webkul\SAASCustomizer\Http\Controllers\Super\TenantController@massDestroy')->defaults('_config', [
                    'redirect' => 'super.tenants.index'
                ])->name('super.tenants.massdelete');

                // Tenant Customer Routes
                Route::get('/customers', 'Webkul\SAASCustomizer\Http\Controllers\Super\TenantCustomersController@index')->defaults('_config', [
                    'view' => 'saas::super.tenants.customers.index'
                ])->name('super.tenants.customers.index');

                // Tenant Product Routes
                Route::get('/products', 'Webkul\SAASCustomizer\Http\Controllers\Super\TenantProductsController@index')->defaults('_config', [
                    'view' => 'saas::super.tenants.products.index'
                ])->name('super.tenants.products.index');

                // Tenant Order Routes
                Route::get('/orders', 'Webkul\SAASCustomizer\Http\Controllers\Super\TenantOrdersController@index')->defaults('_config', [
                    'view' => 'saas::super.tenants.orders.index'
                ])->name('super.tenants.orders.index');
            });

            
            // Super Admin Agents Routes
            Route::get('agents', 'Webkul\SAASCustomizer\Http\Controllers\Super\AgentController@index')->defaults('_config', [
                'view' => 'saas::super.agents.index'
            ])->name('super.agents.index');

            Route::get('agents/create', 'Webkul\SAASCustomizer\Http\Controllers\Super\AgentController@create')->defaults('_config', [
                'view' => 'saas::super.agents.create'
            ])->name('super.agents.create');

            Route::post('agents/create', 'Webkul\SAASCustomizer\Http\Controllers\Super\AgentController@store')->defaults('_config', [
                'redirect' => 'super.agents.index'
            ])->name('super.agents.store');

            Route::get('agents/edit/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\AgentController@edit')->defaults('_config', [
                'view' => 'saas::super.agents.edit'
            ])->name('super.agents.edit');

            Route::put('agents/edit/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\AgentController@update')->defaults('_config', [
                'redirect' => 'super.agents.index'
            ])->name('super.agents.update');

            Route::post('agents/delete/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\AgentController@destroy')->defaults('_config', [
                'redirect' => 'super.agents.index'
            ])->name('super.agents.delete');

            Route::get('agents/confirm/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\AgentController@confirm')->defaults('_config', [
                'view' => 'saas::super.agents.confirm-password'
            ])->name('super.agents.confirm');

            Route::post('agents/confirm/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\AgentController@destroySelf')->defaults('_config', [
                'redirect' => 'super.agents.index'
            ])->name('super.agents.destroy');


            // Locale Routes
            Route::get('locales', 'Webkul\SAASCustomizer\Http\Controllers\Super\LocaleController@index')->defaults('_config', [
                'view' => 'saas::super.locales.index'
            ])->name('super.locales.index');

            Route::get('locales/create', 'Webkul\SAASCustomizer\Http\Controllers\Super\LocaleController@create')->defaults('_config', [
                'view' => 'saas::super.locales.create'
            ])->name('super.locales.create');

            Route::post('locales/create', 'Webkul\SAASCustomizer\Http\Controllers\Super\LocaleController@store')->defaults('_config', [
                'redirect' => 'super.locales.index'
            ])->name('super.locales.store');

            Route::get('locales/edit/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\LocaleController@edit')->defaults('_config', [
                'view' => 'saas::super.locales.edit'
            ])->name('super.locales.edit');

            Route::put('locales/edit/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\LocaleController@update')->defaults('_config', [
                'redirect' => 'super.locales.index'
            ])->name('super.locales.update');

            Route::post('locales/delete/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\LocaleController@destroy')->name('super.locales.delete');


            // Currency Routes
            Route::get('currencies', 'Webkul\SAASCustomizer\Http\Controllers\Super\CurrencyController@index')->defaults('_config', [
                'view' => 'saas::super.currencies.index'
            ])->name('super.currencies.index');

            Route::get('currencies/create', 'Webkul\SAASCustomizer\Http\Controllers\Super\CurrencyController@create')->defaults('_config', [
                'view' => 'saas::super.currencies.create'
            ])->name('super.currencies.create');

            Route::post('currencies/create', 'Webkul\SAASCustomizer\Http\Controllers\Super\CurrencyController@store')->defaults('_config', [
                'redirect' => 'super.currencies.index'
            ])->name('super.currencies.store');

            Route::get('currencies/edit/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\CurrencyController@edit')->defaults('_config', [
                'view' => 'saas::super.currencies.edit'
            ])->name('super.currencies.edit');

            Route::put('currencies/edit/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\CurrencyController@update')->defaults('_config', [
                'redirect' => 'super.currencies.index'
            ])->name('super.currencies.update');

            Route::post('currencies/delete/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\CurrencyController@destroy')->name('super.currencies.delete');


            // Exchange Rates Routes
            Route::get('/exchange_rates', 'Webkul\SAASCustomizer\Http\Controllers\Super\ExchangeRateController@index')->defaults('_config', [
                'view' => 'saas::super.exchange_rates.index'
            ])->name('super.exchange_rates.index');

            Route::get('/exchange_rates/create', 'Webkul\SAASCustomizer\Http\Controllers\Super\ExchangeRateController@create')->defaults('_config', [
                'view' => 'saas::super.exchange_rates.create'
            ])->name('super.exchange_rates.create');

            Route::post('/exchange_rates/create', 'Webkul\SAASCustomizer\Http\Controllers\Super\ExchangeRateController@store')->defaults('_config', [
                'redirect' => 'super.exchange_rates.index'
            ])->name('super.exchange_rates.store');

            Route::get('/exchange_rates/edit/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\ExchangeRateController@edit')->defaults('_config', [
                'view' => 'saas::super.exchange_rates.edit'
            ])->name('super.exchange_rates.edit');

            Route::get('/exchange_rates/update-rates/{service}', 'Webkul\SAASCustomizer\Http\Controllers\Super\ExchangeRateController@updateRates')->name('super.exchange_rates.update-rates');

            Route::put('/exchange_rates/edit/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\ExchangeRateController@update')->defaults('_config', [
                'redirect' => 'super.exchange_rates.index'
            ])->name('super.exchange_rates.update');

            Route::post('/exchange_rates/delete/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\ExchangeRateController@destroy')->name('super.exchange_rates.delete');


            // Super channel routes
            Route::get('channels', 'Webkul\SAASCustomizer\Http\Controllers\Super\ChannelController@index')->defaults('_config', [
                'view' => 'saas::super.channels.index'
            ])->name('super.channels.index');

            Route::get('channels/edit/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\ChannelController@edit')->defaults('_config', [
                'view' => 'saas::super.channels.edit'
            ])->name('super.channels.edit');

            Route::put('channels/edit/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Super\ChannelController@update')->defaults('_config', [
                'redirect' => 'super.channels.index'
            ])->name('super.channels.update');
        });
    });
});

Route::group(['middleware' => ['web', 'company-locale']], function () {
    // company registration routes
    Route::prefix('company')->group(function() {

        //Store front home
        Route::get('/home', 'Webkul\SAASCustomizer\Http\Controllers\Company\HomeController@index')->defaults('_config', [
            'view' => 'saas::companies.home.index'
        ])->name('saas.home.index');

        Route::get('/register', 'Webkul\SAASCustomizer\Http\Controllers\Company\CompanyController@create')->defaults('_config', [
            'view' => 'saas::companies.auth.register'
        ])->name('company.create.index');

        Route::post('/register', 'Webkul\SAASCustomizer\Http\Controllers\Company\CompanyController@store')->name('company.create.store');

        Route::post('/validate/step-one', 'Webkul\SAASCustomizer\Http\Controllers\Company\CompanyController@validateStepOne')->name('company.validate.step-one');

        Route::post('/validate/step-three', 'Webkul\SAASCustomizer\Http\Controllers\Company\CompanyController@validateStepThree')->name('company.validate.step-three');

        Route::get('/seed-data', 'Webkul\SAASCustomizer\Http\Controllers\Company\PurgeController@seedDatabase')->name('company.create.data');
    });
});

Route::group(['middleware' => 'web'], function () {

    Route::prefix('admin')->group(function() {
        Route::post('/login', 'Webkul\SAASCustomizer\Http\Controllers\Admin\SessionController@store')->defaults('_config', [
            'redirect' => 'admin.dashboard.index'
        ])->name('admin.session.store');

        Route::group(['middleware' => ['admin']], function () {

            Route::prefix('company')->group(function() {
                Route::get('/profile', 'Webkul\SAASCustomizer\Http\Controllers\Admin\CompanyProfileController@index')->defaults('_config', [
                    'view' => 'saas::admin.details'
                ])->name('company.profile.index');
    
                Route::post('/profile', 'Webkul\SAASCustomizer\Http\Controllers\Admin\CompanyProfileController@update')->defaults('_config', [
                    'redirect' => 'company.profile.index'
                ])->name('company.profile.update');
    
                Route::get('/address', 'Webkul\SAASCustomizer\Http\Controllers\Admin\CompanyAddressController@index')->defaults('_config', [
                    'view' => 'saas::admin.address.index'
                ])->name('company.address.index');
    
                Route::get('/address/create', 'Webkul\SAASCustomizer\Http\Controllers\Admin\CompanyAddressController@create')->defaults('_config', [
                    'view' => 'saas::admin.address.create'
                ])->name('company.address.create');
    
                Route::post('/address/create', 'Webkul\SAASCustomizer\Http\Controllers\Admin\CompanyAddressController@store')->defaults('_config', [
                    'redirect' => 'company.address.index'
                ])->name('company.address.store');
    
                Route::get('/address/edit/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Admin\CompanyAddressController@edit')->defaults('_config', [
                    'view' => 'saas::admin.address.edit'
                ])->name('company.address.edit');
    
                Route::post('/address/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Admin\CompanyAddressController@update')->defaults('_config', [
                    'redirect' => 'company.address.index'
                ])->name('company.address.update');
    
                Route::post('/address/delete/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Admin\CompanyAddressController@destroy')->defaults('_config', [
                    'redirect' => 'company.address.index'
                ])->name('company.address.delete');
            });
            
            Route::prefix('velocity')->group(function() {
                Route::get('/meta-data', 'Webkul\SAASCustomizer\Http\Controllers\Admin\ConfigurationController@renderMetaData')->defaults('_config', [
                    'view' => 'velocity::admin.meta-info.meta-data'
                ])->name('velocity.admin.meta-data');
            });

            Route::get('/channels/create', 'Webkul\SAASCustomizer\Http\Controllers\Admin\ChannelController@create')->defaults('_config', [
                'view' => 'saas::admin.settings.channels.create'
            ])->name('admin.channels.create');

            Route::post('/channels/create', 'Webkul\SAASCustomizer\Http\Controllers\Admin\ChannelController@store')->defaults('_config', [
                'redirect' => 'admin.channels.index'
            ])->name('admin.channels.store');

            Route::get('/channels/edit/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Admin\ChannelController@edit')->defaults('_config', [
                'view' => 'saas::admin.settings.channels.edit'
            ])->name('admin.channels.edit');

            Route::put('/channels/edit/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Admin\ChannelController@update')->defaults('_config', [
                'redirect' => 'admin.channels.index'
            ])->name('admin.channels.update');

            Route::post('/channels/delete/{id}', 'Webkul\SAASCustomizer\Http\Controllers\Admin\ChannelController@destroy')->name('admin.channels.delete');
        });
    });

    Route::prefix('customer')->group(function () {
        // Login form store
        Route::post('login', 'Webkul\SAASCustomizer\Http\Controllers\Session\SessionController@create')->defaults('_config', [
           'redirect' => 'customer.profile.index',
        ])->name('customer.session.create');
    });
});


Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {

    Route::get('/category-products/{categoryId}', 'Webkul\SAASCustomizer\Http\Controllers\Shop\ShopController@getCategoryProducts')
    ->name('velocity.category.products');
});