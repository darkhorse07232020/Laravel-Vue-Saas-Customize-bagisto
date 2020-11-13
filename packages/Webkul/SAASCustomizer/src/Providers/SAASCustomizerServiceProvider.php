<?php

namespace Webkul\SAASCustomizer\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\AliasLoader;
use Webkul\SAASCustomizer\Company;
use Webkul\SAASCustomizer\Facades\Company as CompanyFacade;
use Webkul\SAASCustomizer\Providers\EventServiceProvider;
use Webkul\Sales\Providers\ModuleServiceProvider;
use Webkul\SAASCustomizer\Http\Middleware\Locale;
use Webkul\SAASCustomizer\Http\Middleware\CompanyLocale;
use Webkul\SAASCustomizer\Http\Middleware\RedirectIfNotSuperAdmin;
use Webkul\SAASCustomizer\Http\Middleware\Bouncer as BouncerMiddleware;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Webkul\SAASCustomizer\Exceptions\Handler;
use Webkul\Core\Tree;

class SAASCustomizerServiceProvider extends ServiceProvider
{
    protected  $model_connections = [
        // Attribute Models Starts
        'Attribute' => [
            'Attribute',
            'AttributeTranslation',
            'AttributeFamily',
            'AttributeGroup',
            'AttributeOption',
            'AttributeOptionTranslation',
        ],

        // Category Models Starts
        'Category'  => [
            'Category',
            'CategoryTranslation',
        ],

        // Checkout Models Starts
        'Checkout'  => [
            'Cart',
            'CartAddress',
            'CartItem',
            'CartPayment',
            'CartShippingRate',
        ],

        // Core Models Starts
        'Core'  => [
            'Channel',
            'CoreConfig',
            'Currency',
            'CurrencyExchangeRate',
            'Locale',
            'Slider',
            'SubscribersList',
        ],

        // Customer Models Starts
        'Customer'  => [
            'Customer',
            'CustomerAddress',
            'CustomerGroup',
            'Wishlist',
        ],

        // Inventory Models Starts
        'Inventory'  => [
            'InventorySource',
        ],

        // Product Models Starts
        'Product'  => [
            'Product',
            'ProductAttributeValue',
            'ProductFlat',
            'ProductImage',
            'ProductInventory',
            'ProductOrderedInventory',
            'ProductReview',
        ],

        // Sales Models Starts
        'Sales'  => [
            'Invoice',
            'InvoiceItem',
            'Order',
            'OrderAddress',
            'OrderItem',
            'OrderPayment',
            'Shipment',
            'Refund',
            'RefundItem',
            'DownloadableLinkPurchased',
        ],

        // Tax Models Starts
        'Tax'  => [
            'TaxCategory',
            'TaxMap',
            'TaxRate',
        ],

        // User Models Starts
        'User'  => [
            'Admin',
            'Role',
        ],

        // CartRule Models Starts
        'CartRule'  => [
            'CartRule',
            'CartRuleCoupon',
        ],

        // CatalogRule Models Starts
        'CatalogRule'  => [
            'CatalogRule',
            'CatalogRuleProduct',
            'CatalogRuleProductPrice',
        ],

        // CMS Models Starts
        'CMS'  => [
            'CmsPage',
            'CmsPageTranslation',
        ],

        // Velocity Models Starts
        'Velocity'  => [
            'Content',
            'ContentTranslation',
            'OrderBrand',
            'VelocityMetadata',
        ],
    ];

    protected $skip_observer = [
        'TaxMap',
    ];

    public function boot(Router $router)
    {
        include __DIR__ . '/../Http/helpers.php';
        
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'saas');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'saas');

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('vendor/webkul/saas/assets'),
        ], 'public');

        $this->loadGloableVariables();

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/UI/particals.blade.php' => resource_path('themes/velocity/views/UI/particals.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/guest/compare/compare-products.blade.php' => resource_path('themes/velocity/views/guest/compare/compare-products.blade.php'),
        ]);
        
        $router->aliasMiddleware('super-locale', Locale::class);
        $router->aliasMiddleware('company-locale', CompanyLocale::class);
        $router->aliasMiddleware('super-admin', RedirectIfNotSuperAdmin::class);
        $router->aliasMiddleware('superadmins', BouncerMiddleware::class);

        //over ride system's default validation
        $this->registerPresenceVerifier();

        //over ride system's default validation DB presence verifier
        $this->registerValidationFactory();

        //model observer for all the core models of Bagisto
        $this->bootModelObservers();

        //over ride all existing core models of Bagisto
        $this->overrideModels();

        Validator::extend('slug', 'Webkul\SAASCustomizer\Contracts\Validations\Host@passes');
        Validator::extend('code', 'Webkul\SAASCustomizer\Contracts\Validations\Code@passes');

        $this->app->bind(
            ExceptionHandler::class,
            Handler::class
        );

        $this->composeView();
    }

    /**
     * Compose View
     */
    public function composeView()
    {
        view()->composer(['saas::super.layouts.nav-left', 'saas::super.layouts.nav-aside', 'saas::super.layouts.tabs'], function ($view) {
            $tree = Tree::create();

            foreach (config('menu.super-admin') as $index => $item) {
                $tree->add($item, 'menu');
            }

            $tree->items = company()->sortItems($tree->items);

            $view->with('menu', $tree);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
        
        $this->app->register(ModuleServiceProvider::class);

        $this->registerConfig();

        $this->registerFacades();

        //override DB facade
        $this->app->singleton('db', function ($app) {
            return new \Webkul\SAASCustomizer\Database\DatabaseManager($app, $app['db.factory']);
        });

        $this->commands([
            \Webkul\SAASCustomizer\Commands\Console\GenerateSU::class
        ]);
    }

    public function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/purge-pool.php', 'purge-pool'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/super-menu.php', 'menu.super-admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/excluded-sites.php', 'excluded-sites'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/super-system.php', 'company'
        );
    }

    /**
     * Register the validation factory.
     *
     * @return void
     */
    protected function registerValidationFactory()
    {
        $this->app->singleton('validator', function ($app) {
            $validator = new \Illuminate\Validation\Factory($app['translator'], $app);

            // The validation presence verifier is responsible for determining the existence of
            // values in a given data collection which is typically a relational database or
            // other persistent data stores. It is used to check for "uniqueness" as well.
            if (isset($app['db'], $app['validation.presence'])) {
                $validator->setPresenceVerifier($app['validation.presence']);
            }

            return $validator;
        });
    }

    /**
     * Register the database presence verifier.
     *
     * @return void
     */
    protected function registerPresenceVerifier()
    {
        $this->app->singleton('validation.presence', function ($app) {
            return new \Webkul\SAASCustomizer\Validation\DatabasePresenceVerifier($app['db']);
        });
    }

    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerFacades()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('company', CompanyFacade::class);

        $this->app->singleton('company', function () {
            return app()->make(Company::class);
        });
    }

    /**
     * Override the existing models
     */
    public function overrideModels()
    {
        foreach ($this->model_connections as $dir_path => $modelClasses) {
            foreach ($modelClasses as $modelClass) {
                $this->app->concord->registerModel(
                    "Webkul\\{$dir_path}\Contracts\\{$modelClass}", "Webkul\SAASCustomizer\Models\\{$dir_path}\\{$modelClass}"
                );
            }
        }
    }

    /**
     * Boot all the model observers
     */
    public function bootModelObservers()
    {
        foreach ($this->model_connections as $dir_path => $modelClasses) {
            foreach ($modelClasses as $modelClass) {
                if (! in_array($modelClass, $this->skip_observer)) {
                    $model_class = "\Webkul\SAASCustomizer\Models\\{$dir_path}\\{$modelClass}";
                    $model_class::observe("\Webkul\SAASCustomizer\Observers\\{$dir_path}\\{$modelClass}Observer");
                }
            }
        }

        \Webkul\SAASCustomizer\Models\CompanyAddress::observe(\Webkul\SAASCustomizer\Observers\CompanyAddressObserver::class);
    }

    // this function will provide global variables shared by view (blade files)
    private function loadGloableVariables()
    {
        $velocityHelper = app('Webkul\SAASCustomizer\Helpers\Helper');
        $velocityMetaData = $velocityHelper->getVelocityMetaData();
        
        view()->share('showRecentlyViewed', true);
        view()->share('velocityMetaData', $velocityMetaData);

        return true;
    }
}