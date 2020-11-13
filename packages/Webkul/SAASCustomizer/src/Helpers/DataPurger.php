<?php

namespace Webkul\SAASCustomizer\Helpers;

use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Inventory\Repositories\InventorySourceRepository;
use Webkul\Core\Repositories\LocaleRepository;
use Webkul\Core\Repositories\CurrencyRepository;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Attribute\Repositories\AttributeGroupRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\CMS\Repositories\CmsRepository;
use Webkul\Velocity\Repositories\VelocityMetadataRepository;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use DB;
use Company;

/**
 * Class meant for preparing functional and sample data required for functioning of a new seller
 */
class DataPurger
{
    /**
     * CompanyRepository instance
     */
    protected $companyRepository;

    /**
     * CategoryRepository instance
     */
    protected $categoryRepository;

    /**
     * InventorySourceRepository instance
     */
    protected $inventorySourceRepository;

    /**
     * LocaleRepository instance
     */
    protected $localeRepository;

    /**
     * CurrencyRepository instance
     */
    protected $currencyRepository;

    /**
     * ChannelRepository instance
     */
    protected $channelRepository;

    /**
     * CoreConfigRepository instance
     */
    protected $coreConfigRepository;

    /**
     * AttributeRepository instance
     */
    protected $attributeRepository;

    /**
     * AttributeFamilyRepository instance
     */
    protected $attributeFamilyRepository;

    /**
     * AttributeGroupRepository instance
     */
    protected $attributeGroupRepository;

    /**
     * CustomerGroupRepository instance
     */
    protected $customerGroupRepository;

    /**
     * CmsRepository instance
     */
    protected $cmsRepository;

    /**
     * VelocityMetadataRepository instance
     */
    protected $velocityMetadataRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        InventorySourceRepository $inventorySourceRepository,
        LocaleRepository $localeRepository,
        CurrencyRepository $currencyRepository,
        ChannelRepository $channelRepository,
        CoreConfigRepository $coreConfigRepository,
        AttributeRepository $attributeRepository,
        AttributeFamilyRepository $attributeFamilyRepository,
        AttributeGroupRepository $attributeGroupRepository,
        CustomerGroupRepository $customerGroupRepository,
        CmsRepository $cmsRepository,
        velocityMetadataRepository $velocityMetadataRepository
    )
    {
        $this->categoryRepository = $categoryRepository;

        $this->inventorySourceRepository = $inventorySourceRepository;

        $this->localeRepository = $localeRepository;

        $this->currencyRepository = $currencyRepository;

        $this->channelRepository = $channelRepository;

        $this->coreConfigRepository = $coreConfigRepository;
        
        $this->attributeRepository = $attributeRepository;

        $this->attributeFamilyRepository = $attributeFamilyRepository;

        $this->attributeGroupRepository = $attributeGroupRepository;

        $this->customerGroupRepository = $customerGroupRepository;

        $this->cmsRepository = $cmsRepository;

        $this->velocityMetadataRepository = $velocityMetadataRepository;
    }

    /**
     * To prepare the country state data for
     * admin and customers country & state fields
     * auto population
     */
    public function prepareCountryStateData()
    {
        $countries = json_decode(file_get_contents(base_path().'/packages/Webkul/Core/src/Data/countries.json'), true);

        DB::table('countries')->insert($countries);

        $states = json_decode(file_get_contents(base_path().'/packages/Webkul/Core/src/Data/states.json'), true);

        DB::table('country_states')->insert($states);

        Log::info("Info:- prepareCountryStateData() created.");

        return true;
    }

    /**
     * Creates a default locale
     */
    public function prepareLocaleData()
    {
        $companyRepository = Company::getCurrent();

        $data = [
            'code'          => 'en',
            'name'          => 'English',
            'company_id'    => $companyRepository->id
        ];
        
        Log::info("Info:- prepareLocaleData() created for company " . $companyRepository->domain . ".");

        return $this->localeRepository->create($data);
    }

    /**
     * Prepares a default currency
     */
    public function prepareCurrencyData()
    {
        $companyRepository = Company::getCurrent();

        $data = [
            'code'          => 'USD',
            'name'          => 'US Dollar',
            'symbol'        => '$',
            'company_id'    => $companyRepository->id
        ];

        Log::info("Info:- prepareCurrencyData() created for company " . $companyRepository->domain . ".");

        return $this->currencyRepository->create($data);
    }

    /**
     * Prepares category data
     */
    public function prepareCategoryData()
    {
        $companyRepository = Company::getCurrent();

        $data = [
            'position'          => '1',
            'image'             => NULL,
            'status'            => '1',
            'parent_id'         => NULL,
            'name'              => 'Root',
            'slug'              => 'root',
            'description'       => 'Root',
            'meta_title'        => '',
            'meta_description'  => '',
            'meta_keywords'     => '',
            'locale'            => 'all',
            'company_id'        => $companyRepository->id
        ];

        Log::info("Info:- prepareCategoryData() created for company " . $companyRepository->domain . ".");

        return $this->categoryRepository->create($data);
    }

    /**
     * Prepares data for a default inventory
     */
    public function prepareInventoryData()
    {
        $companyRepository = Company::getCurrent();

        $data = [
            'code'              => 'default',
            'name'              => 'Default',
            'contact_name'      => 'Detroit Warehouse',
            'contact_email'     => 'warehouse@example.com',
            'contact_number'    => '123456789',
            'status'            => 1,
            'country'           => 'US',
            'state'             => 'MI',
            'street'            => '12th Street',
            'city'              => 'Detroit',
            'postcode'          => '48127',
            'company_id'        => $companyRepository->id
        ];

        Log::info("Info:- prepareInventoryData() created for company " . $companyRepository->domain . ".");

        return $this->inventorySourceRepository->create($data);
    }

    /**
     * Prepares a default channel
     */
    public function prepareChannelData()
    {
        $companyRepository = Company::getCurrent();

        $localeRepository = $this->prepareLocaleData();

        $currencyRepository = $this->prepareCurrencyData();

        $categoryRepository = $this->prepareCategoryData();

        $inventorySourceRepository = $this->prepareInventoryData();

        $data = [
            'company_id'        => $companyRepository->id,
            'code'              => $companyRepository->username,
            'name'              => 'Default Channel',
            'description'       => 'Default Channel',
            'inventory_sources' => [
                0               => $inventorySourceRepository->id
            ],
            'root_category_id'  => $categoryRepository->id,
            'hostname'          => $companyRepository->domain,
            'locales'           => [
                0               => $localeRepository->id
            ],
            'default_locale_id' => $localeRepository->id,
            'currencies'        => [
                0               => $currencyRepository->id
            ],
            'base_currency_id'  => $currencyRepository->id,
            'theme'             => 'velocity',
            'home_page_content' => '<p>@include("shop::home.slider") @include("shop::home.featured-products") @include("shop::home.new-products")</p><div class="banner-container"><div class="left-banner"><img src="https://s3-ap-southeast-1.amazonaws.com/cdn.uvdesk.com/website/1/201902045c581f9494b8a1.png" /></div><div class="right-banner"><img src="https://s3-ap-southeast-1.amazonaws.com/cdn.uvdesk.com/website/1/201902045c581fb045cf02.png" /> <img src="https://s3-ap-southeast-1.amazonaws.com/cdn.uvdesk.com/website/1/201902045c581fc352d803.png" /></div></div>',

            'footer_content' => '<div class="list-container"><span class="list-heading">Quick Links</span><ul class="list-group"><li><a href="@php echo route(\'shop.cms.page\', \'about-us\') @endphp">About Us</a></li><li><a href="@php echo route(\'shop.cms.page\', \'return-policy\') @endphp">Return Policy</a></li><li><a href="@php echo route(\'shop.cms.page\', \'refund-policy\') @endphp">Refund Policy</a></li><li><a href="@php echo route(\'shop.cms.page\', \'terms-conditions\') @endphp">Terms and conditions</a></li><li><a href="@php echo route(\'shop.cms.page\', \'terms-of-use\') @endphp">Terms of Use</a></li><li><a href="@php echo route(\'shop.cms.page\', \'contact-us\') @endphp">Contact Us</a></li></ul></div><div class="list-container"><span class="list-heading">Connect With Us</span><ul class="list-group"><li><a href="#"><span class="icon icon-facebook"></span>Facebook </a></li><li><a href="#"><span class="icon icon-twitter"></span> Twitter </a></li><li><a href="#"><span class="icon icon-instagram"></span> Instagram </a></li><li><a href="#"> <span class="icon icon-google-plus"></span>Google+ </a></li><li><a href="#"> <span class="icon icon-linkedin"></span>LinkedIn </a></li></ul></div>',
            'home_seo' => json_encode([
                'meta_title'        => "Default Channel",
                'meta_description'  => "Default Channel Description",
                'meta_keywords'     => "Default Channel"
            ]),
        ];
        
        $channelRepository = $this->channelRepository->create($data);

        if ( isset($channelRepository->id) ) {
            $companyRepository->channel_id = $channelRepository->id;
            $companyRepository->save();
        }

        Log::info("Info:- prepareChannelData() created for company " . $companyRepository->domain . ".");

        return $channelRepository;
    }

    /**
     * Prepare data for the customer groups
     */
    public function prepareCustomerGroupData()
    {
        $companyRepository = Company::getCurrent();
        $data = [
            'guest'     => [
                'code'              => 'guest',
                'name'              => 'Guest',
                'is_user_defined'   => 0,
                'company_id'        => $companyRepository->id
            ],
            'general'   => [
                'id'                => 1,
                'code'              => 'general',
                'name'              => 'General',
                'is_user_defined'   => 0,
                'company_id'        => $companyRepository->id
            ],
            'wholesale' => [
                'id'                => 2,
                'code'              => 'wholesale',
                'name'              => 'Wholesale',
                'is_user_defined'   => 0,
                'company_id'        => $companyRepository->id
            ]
        ];

        Log::info("Info:- prepareCustomerGroupData() created for company " . $companyRepository->domain . ".");

        return [
            'guest'     => $this->customerGroupRepository->create($data['guest']),
            'default'   => $this->customerGroupRepository->create($data['general']),
            'wholesale' => $this->customerGroupRepository->create($data['wholesale'])
        ];
    }

    /**
     * Prepare Attribute Data
     */
    public function prepareAttributeData()
    {
        $companyRepository = Company::getCurrent();

        $sku = ['code' => 'sku','admin_name' => 'SKU','type' => 'text','validation' => NULL,'position' => '1','is_required' => '1','is_unique' => '1','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'SKU']];

        $this->attributeRepository->create($sku);

        $name = ['code' => 'name', 'admin_name' => 'Name', 'type' => 'text', 'validation' => NULL, 'position' => '2', 'is_required' => '1', 'is_unique' => '0', 'value_per_locale' => '1', 'value_per_channel' => '1', 'is_filterable' => '0', 'is_configurable' => '0', 'is_comparable' => '1', 'is_user_defined' => '0', 'is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id,'en' => ['name' => 'Name']];

        $this->attributeRepository->create($name);

        $url_key = ['code' => 'url_key', 'admin_name' => 'URL Key', 'type' => 'text', 'validation' => NULL, 'position' => '3', 'is_required' => '1', 'is_unique' => '1', 'value_per_locale' => '0', 'value_per_channel' => '0', 'is_filterable' => '0', 'is_configurable' => '0', 'is_user_defined' => '0', 'is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'URL Key']];

        $this->attributeRepository->create($url_key);

        $taxCategoryId = ['code' => 'tax_category_id', 'admin_name' => 'Tax Category', 'type' => 'select', 'validation' => NULL, 'position' => '4', 'is_required' => '0', 'is_unique' => '0', 'value_per_locale' => '0', 'value_per_channel' => '1', 'is_filterable' => '0', 'is_configurable' => '0', 'is_user_defined' => '0', 'is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Tax Category']];

        $this->attributeRepository->create($taxCategoryId);

        $new = ['code' => 'new', 'admin_name' => 'New', 'type' => 'boolean', 'validation' => NULL, 'position' => '5', 'is_required' => '0', 'is_unique' => '0', 'value_per_locale' => '0', 'value_per_channel' => '0', 'is_filterable' => '0','is_configurable' => '0', 'is_user_defined' => '0', 'is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'New']];

        $this->attributeRepository->create($new);

        $featured = ['id' => '6', 'code' => 'featured', 'admin_name' => 'Featured', 'type' => 'boolean', 'validation' => NULL, 'position' => '6', 'is_required' => '0', 'is_unique' => '0', 'value_per_locale' => '0', 'value_per_channel' => '0', 'is_filterable' => '0', 'is_configurable' => '0', 'is_user_defined' => '0', 'is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Featured']];

        $this->attributeRepository->create($featured);

        $visibleIndividually = ['code' => 'visible_individually','admin_name' => 'Visible Individually','type' => 'boolean','validation' => NULL,'position' => '7','is_required' => '1','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Visible Individually']];

        $this->attributeRepository->create($visibleIndividually);

        $status = ['code' => 'status','admin_name' => 'Status','type' => 'boolean','validation' => NULL,'position' => '8','is_required' => '1','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Status']];

        $this->attributeRepository->create($status);

        $shortDesc = ['code' => 'short_description','admin_name' => 'Short Description','type' => 'textarea','validation' => NULL,'position' => '9','is_required' => '1','is_unique' => '0','value_per_locale' => '1','value_per_channel' => '1','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Short Description']];

        $this->attributeRepository->create($shortDesc);

        $desc = ['code' => 'description','admin_name' => 'Description','type' => 'textarea','validation' => NULL,'position' => '10','is_required' => '1','is_unique' => '0','value_per_locale' => '1','value_per_channel' => '1','is_filterable' => '0','is_configurable' => '0', 'is_comparable' => '1','is_user_defined' => '0','is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Description']];

        $this->attributeRepository->create($desc);

        $price = ['code' => 'price','admin_name' => 'Price','type' => 'price','validation' => 'decimal','position' => '11','is_required' => '1','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '1','is_configurable' => '0', 'is_comparable' => '1','is_user_defined' => '0','is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Price']];

        $this->attributeRepository->create($price);

        $cost = ['code' => 'cost','admin_name' => 'Cost','type' => 'price','validation' => 'decimal','position' => '12','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '1','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '1','is_visible_on_front' => '0', 'use_in_flat' => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Cost']];

        $this->attributeRepository->create($cost);

        $specialPrice = ['code' => 'special_price','admin_name' => 'Special Price','type' => 'price','validation' => 'decimal','position' => '13','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Special Price']];

        $this->attributeRepository->create($specialPrice);

        $specialFrom = ['code' => 'special_price_from','admin_name' => 'Special Price From','type' => 'date','validation' => NULL,'position' => '14','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '1','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Special Price From']];

        $this->attributeRepository->create($specialFrom);

        $specialTo = ['code' => 'special_price_to','admin_name' => 'Special Price To','type' => 'date','validation' => NULL,'position' => '15','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '1','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Special Price To']];

        $this->attributeRepository->create($specialTo);

        $metaTitle = ['code' => 'meta_title','admin_name' => 'Meta Title','type' => 'textarea','validation' => NULL,'position' => '16','is_required' => '0','is_unique' => '0','value_per_locale' => '1','value_per_channel' => '1','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Meta Title']];

        $this->attributeRepository->create($metaTitle);

        $metaKeywords = ['code' => 'meta_keywords','admin_name' => 'Meta Keywords','type' => 'textarea','validation' => NULL,'position' => '17','is_required' => '0','is_unique' => '0','value_per_locale' => '1','value_per_channel' => '1','is_filterable' => '0', 'is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Meta Keywords']];

        $this->attributeRepository->create($metaKeywords);

        $metaDesc = ['code' => 'meta_description','admin_name' => 'Meta Description','type' => 'textarea','validation' => NULL, 'position' => '18','is_required' => '0','is_unique' => '0','value_per_locale' => '1','value_per_channel' => '1','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '1','is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Meta Description']];

        $this->attributeRepository->create($metaDesc);

        $width = ['code' => 'width','admin_name' => 'Width','type' => 'text','validation' => 'decimal','position' => '19','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '1','is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Width']];

        $this->attributeRepository->create($width);

        $height = ['code' => 'height','admin_name' => 'Height','type' => 'text','validation' => 'decimal','position' => '20','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '1','is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Height']];

        $this->attributeRepository->create($height);

        $depth = ['code' => 'depth','admin_name' => 'Depth','type' => 'text','validation' => 'decimal','position' => '21','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '1','is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Depth']];

        $this->attributeRepository->create($depth);

        $weight = ['code' => 'weight','admin_name' => 'Weight','type' => 'text','validation' => 'decimal','position' => '22','is_required' => '1','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Weight']];

        $this->attributeRepository->create($weight);

        $color = ['code' => 'color','admin_name' => 'Color','type' => 'select','validation' => NULL,'position' => '23','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '1','is_configurable' => '1','is_user_defined' => '1','is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Color'], 'options' => [
            'option_0' => ['admin_name' => 'Red', 'en' => ['label' => 'Red'], 'sort_order' => '1'],
            'option_1' => ['admin_name' => 'Green', 'en' => ['label' => 'Green'],'sort_order' => '2'],
            'option_2' => ['admin_name' => 'Yellow', 'en' => ['label' => 'Yellow'], 'sort_order' => '3'],
            'option_3' => ['admin_name' => 'Black', 'en' => ['label' => 'Black'], 'sort_order' => '4'],
            'option_4' => ['admin_name' => 'White', 'en' => ['label' => 'White'], 'sort_order' => '5']
        ]];

        $this->attributeRepository->create($color);

        $size = ['code' => 'size','admin_name' => 'Size','type' => 'select','validation' => NULL,'position' => '24','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '1','is_configurable' => '1','is_user_defined' => '1','is_visible_on_front' => '0', 'use_in_flat'   => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Size'], 'options' => [
            'option_0' => ['id' => '6','admin_name' => 'S', 'en' => ['label' => 'S'], 'sort_order' => '1'],
            'option_1' => ['id' => '7','admin_name' => 'M', 'en' => ['label' => 'M'], 'sort_order' => '2'],
            'option_2' => ['id' => '8','admin_name' => 'L', 'en' => ['label' => 'L'], 'sort_order' => '3'],
            'option_3' => ['id' => '9','admin_name' => 'XL', 'en' => ['label' => 'XL'], 'sort_order' => '4']
        ]];

        $this->attributeRepository->create($size);

        $brand = ['code' => 'brand','admin_name' => 'Brand','type' => 'select','validation' => NULL,'position' => '25','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '1','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '1', 'use_in_flat' => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Brand']];

        $this->attributeRepository->create($brand);

        $guest_checkout = ['code' => 'guest_checkout','admin_name' => 'Guest Checkout','type' => 'boolean','validation' => NULL,'position' => '8','is_required' => '1','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'use_in_flat' => '1', 'company_id' => $companyRepository->id, 'en' => ['name' => 'Guest Checkout']];

        $this->attributeRepository->create($guest_checkout);

        Log::info("Info:- prepareAttributeData() created for company " . $companyRepository->domain . ".");

        $this->prepareAttributeFamilyData();

        $this->prepareAttributeGroupData();

        return true;
    }

    /**
     * To prepare the attribute family
     */
    public function prepareAttributeFamilyData()
    {
        $companyRepository = Company::getCurrent();

        $data = [
            'code'              => 'default',
            'name'              => 'Default',
            'status'            => '0',
            'is_user_defined'   => '1',
            'company_id'        => $companyRepository->id
        ];

        Log::info("Info:- prepareAttributeFamilyData() created for company " . $companyRepository->domain . ".");

        return $this->attributeFamilyRepository->create($data);
    }

    /**
     * To prepare the attribute group mappings
     */
    public function prepareAttributeGroupData()
    {
        $companyRepository = Company::getCurrent();

        $attributeFamilyRepository = $this->attributeFamilyRepository->findOneWhere([
            'company_id'    => $companyRepository->id
        ]);

        $attributes = $this->attributeRepository->all();

        $group1 = ['sku', 'name', 'url_key', 'tax_category_id', 'new', 'featured', 'visible_individually', 'status', 'color', 'size', 'brand', 'guest_checkout'];
        $group2 = ['short_description', 'description'];
        $group3 = ['meta_title', 'meta_keywords', 'meta_description'];
        $group4 = ['price', 'cost', 'special_price', 'special_price_from', 'special_price_to'];
        $group5 = ['width', 'height', 'depth', 'weight'];

        // creating group 1
        $attributeGroupRepository = $this->attributeGroupRepository->create([
            'name'                  => 'General',
            'position'              => '1',
            'is_user_defined'       => '0',
            'attribute_family_id'   => $attributeFamilyRepository->id,
            'company_id'            => $companyRepository->id
        ]);

        $i = 1;
        foreach($group1 as $code) {
            $i++;

            foreach ($attributes as $value) {
                if($value->code == $code) {
                    DB::table('attribute_group_mappings')->insert([
                        [
                            'attribute_id'          => $value->id,
                            'attribute_group_id'    => $attributeGroupRepository->id,
                            'position'              => $i
                        ]
                    ]);
                }
            }
        }

        // creating group 2
        $attributeGroupRepository = $this->attributeGroupRepository->create([
            'name'                  => 'Description',
            'position'              => '2',
            'is_user_defined'       => '0',
            'attribute_family_id'   => $attributeFamilyRepository->id,
            'company_id'            => $companyRepository->id
        ]);

        $i = 1;
        foreach($group2 as $code) {
            $i++;

            foreach ($attributes as $value) {
                if($value->code == $code) {
                    DB::table('attribute_group_mappings')->insert([
                        [
                            'attribute_id'          => $value->id,
                            'attribute_group_id'    => $attributeGroupRepository->id,
                            'position'              => $i
                        ]
                    ]);
                }
            }
        }

        // creating group 3
        $attributeGroupRepository = $this->attributeGroupRepository->create([
            'name'                  => 'Meta Description',
            'position'              => '3',
            'is_user_defined'       => '0',
            'attribute_family_id'   => $attributeFamilyRepository->id,
            'company_id'            => $companyRepository->id
        ]);

        $i = 1;
        foreach($group3 as $code) {
            $i++;

            foreach ($attributes as $value) {
                if($value->code == $code) {
                    DB::table('attribute_group_mappings')->insert([
                        [
                            'attribute_id'          => $value->id,
                            'attribute_group_id'    => $attributeGroupRepository->id,
                            'position'              => $i
                        ]
                    ]);
                }
            }
        }

        // creating group 4
        $attributeGroupRepository = $this->attributeGroupRepository->create([
            'name'                  => 'Price',
            'position'              => '4',
            'is_user_defined'       => '0',
            'attribute_family_id'   => $attributeFamilyRepository->id,
            'company_id'            => $companyRepository->id
        ]);

        $i = 1;
        foreach($group4 as $code) {
            $i++;

            foreach ($attributes as $value) {
                if($value->code == $code) {
                    DB::table('attribute_group_mappings')->insert([
                        [
                            'attribute_id'          => $value->id,
                            'attribute_group_id'    => $attributeGroupRepository->id,
                            'position'              => $i
                        ]
                    ]);
                }
            }
        }

        // creating group 5
        $attributeGroupRepository = $this->attributeGroupRepository->create([
            'name'                  => 'Shipping',
            'position'              => '5',
            'is_user_defined'       => '0',
            'attribute_family_id'   => $attributeFamilyRepository->id,
            'company_id'            => $companyRepository->id
        ]);

        $i = 1;
        foreach($group5 as $code) {
            $i++;

            foreach ($attributes as $value) {
                if($value->code == $code) {
                    DB::table('attribute_group_mappings')->insert([
                        [
                            'attribute_id'          => $value->id,
                            'attribute_group_id'    => $attributeGroupRepository->id,
                            'position'              => $i
                        ]
                    ]);
                }
            }
        }

        Log::info("Info:- prepareAttributeGroupData() created for company " . $companyRepository->domain . ".");

        return true;
    }

    /**
     * To prepare the cms pages data for the seller's shop
     */
    public function prepareCMSPagesData()
    {
        $companyRepository = Company::getCurrent();

        $localeRepository = $this->localeRepository->findOneWhere([
            'company_id'    => $companyRepository->id
        ]);

        DB::table('cms_pages')->insert([
            [
                'company_id' => $companyRepository->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

        $cmsRepository = DB::table('cms_pages')->where('company_id', $companyRepository->id)->orderBy('id', 'desc')->limit(1)->get()->first();

        DB::table('cms_page_translations')->insert([
            [
                'locale' => $localeRepository->code,
                'cms_page_id' => $cmsRepository->id,
                'company_id' => $companyRepository->id,
                'url_key' => 'about-us',
                'html_content' => '<div class="static-container">
                                   <div class="mb-5">About us page content</div>
                                   </div>',
                'page_title' => 'About Us',
                'meta_title' => 'about us',
                'meta_description' => '',
                'meta_keywords' => 'aboutus'
            ]
        ]);

        DB::table('cms_pages')->insert([
            [
                'company_id' => $companyRepository->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

        $cmsRepository = DB::table('cms_pages')->where('company_id', $companyRepository->id)->orderBy('id', 'desc')->limit(1)->get()->first();

        DB::table('cms_page_translations')->insert([
            [
                'locale' => $localeRepository->code,
                'cms_page_id' => $cmsRepository->id,
                'company_id' => $companyRepository->id,
                'url_key' => 'return-policy',
                'html_content' => '<div class="static-container">
                                   <div class="mb-5">Return policy page content</div>
                                   </div>',
                'page_title' => 'Return Policy',
                'meta_title' => 'return policy',
                'meta_description' => '',
                'meta_keywords' => 'return, policy'
            ]
        ]);

        DB::table('cms_pages')->insert([
            [
                'company_id' => $companyRepository->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

        $cmsRepository = DB::table('cms_pages')->where('company_id', $companyRepository->id)->orderBy('id', 'desc')->limit(1)->get()->first();

        DB::table('cms_page_translations')->insert([
            [
                'locale' => $localeRepository->code,
                'cms_page_id' => $cmsRepository->id,
                'company_id' => $companyRepository->id,
                'url_key' => 'refund-policy',
                'html_content' => '<div class="static-container">
                                   <div class="mb-5">Refund policy page content</div>
                                   </div>',
                'page_title' => 'Refund Policy',
                'meta_title' => 'Refund policy',
                'meta_description' => '',
                'meta_keywords' => 'refund, policy'
            ]
        ]);

        DB::table('cms_pages')->insert([
            [
                'company_id' => $companyRepository->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

        $cmsRepository = DB::table('cms_pages')->where('company_id', $companyRepository->id)->orderBy('id', 'desc')->limit(1)->get()->first();

        DB::table('cms_page_translations')->insert([
            [
                'locale' => $localeRepository->code,
                'cms_page_id' => $cmsRepository->id,
                'company_id' => $companyRepository->id,
                'url_key' => 'terms-conditions',
                'html_content' => '<div class="static-container">
                                   <div class="mb-5">Terms & conditions page content</div>
                                   </div>',
                'page_title' => 'Terms & Conditions',
                'meta_title' => 'Terms & Conditions',
                'meta_description' => '',
                'meta_keywords' => 'term, conditions'
            ]
        ]);

        DB::table('cms_pages')->insert([
            [
                'company_id' => $companyRepository->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

        $cmsRepository = DB::table('cms_pages')->where('company_id', $companyRepository->id)->orderBy('id', 'desc')->limit(1)->get()->first();

        DB::table('cms_page_translations')->insert([
            [
                'locale' => $localeRepository->code,
                'cms_page_id' => $cmsRepository->id,
                'company_id' => $companyRepository->id,
                'url_key' => 'terms-of-use',
                'html_content' => '<div class="static-container">
                                   <div class="mb-5">Terms of use page content</div>
                                   </div>',
                'page_title' => 'Terms of use',
                'meta_title' => 'Terms of use',
                'meta_description' => '',
                'meta_keywords' => 'term, use'
            ]
        ]);

        DB::table('cms_pages')->insert([
            [
                'company_id' => $companyRepository->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

        $cmsRepository = DB::table('cms_pages')->where('company_id', $companyRepository->id)->orderBy('id', 'desc')->limit(1)->get()->first();

        DB::table('cms_page_translations')->insert([
            [
                'locale' => $localeRepository->code,
                'cms_page_id' => $cmsRepository->id,
                'company_id' => $companyRepository->id,
                'url_key' => 'contact-us',
                'html_content' => '<div class="static-container">
                                   <div class="mb-5">Contact us page content</div>
                                   </div>',
                'page_title' => 'Contact Us',
                'meta_title' => 'Contact Us',
                'meta_description' => '',
                'meta_keywords' => 'contact, us'
            ]
        ]);

        Log::info("Info:- prepareCMSPagesData() created for company " . $companyRepository->domain . ".");

        return true;
    }

    /**
     * To prepare the Velocity Theme data for the tenant's shop
     */
    public function prepareVelocityData()
    {
        $companyRepository = Company::getCurrent();
            
        $data = [
            'company_id'            => $companyRepository->id,
            'home_page_content'     => "<p>@include('shop::home.advertisements.advertisement-four')@include('shop::home.featured-products') @include('shop::home.product-policy') @include('shop::home.advertisements.advertisement-three') @include('shop::home.new-products') @include('shop::home.advertisements.advertisement-two')</p>",

            'footer_left_content'   => trans('velocity::app.admin.meta-data.footer-left-raw-content'),

            'footer_middle_content' => '<div class="col-lg-6 col-md-12 col-sm-12 no-padding"><ul type="none"><li><a href="https://webkul.com/about-us/company-profile/">About Us</a></li><li><a href="https://webkul.com/about-us/company-profile/">Customer Service</a></li><li><a href="https://webkul.com/about-us/company-profile/">What&rsquo;s New</a></li><li><a href="https://webkul.com/about-us/company-profile/">Contact Us </a></li></ul></div><div class="col-lg-6 col-md-12 col-sm-12 no-padding"><ul type="none"><li><a href="https://webkul.com/about-us/company-profile/"> Order and Returns </a></li><li><a href="https://webkul.com/about-us/company-profile/"> Payment Policy </a></li><li><a href="https://webkul.com/about-us/company-profile/"> Shipping Policy</a></li><li><a href="https://webkul.com/about-us/company-profile/"> Privacy and Cookies Policy </a></li></ul></div>',

            'slider'                => 1,

            'subscription_bar_content' => '<div class="social-icons col-lg-6"><a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-facebook" title="facebook"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-twitter" title="twitter"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-linked-in" title="linkedin"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-pintrest" title="Pinterest"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-youtube" title="Youtube"></i> </a> <a href="https://webkul.com" target="_blank" class="unset" rel="noopener noreferrer"><i class="fs24 within-circle rango-instagram" title="instagram"></i></a></div>',

            'product_policy'        => '<div class="row col-12 remove-padding-margin"><div class="col-lg-4 col-sm-12 product-policy-wrapper"><div class="card"><div class="policy"><div class="left"><i class="rango-van-ship fs40"></i></div> <div class="right"><span class="font-setting fs20">Free Shipping on Order $20 or More</span></div></div></div></div> <div class="col-lg-4 col-sm-12 product-policy-wrapper"><div class="card"><div class="policy"><div class="left"><i class="rango-exchnage fs40"></i></div> <div class="right"><span class="font-setting fs20">Product Replace &amp; Return Available </span></div></div></div></div> <div class="col-lg-4 col-sm-12 product-policy-wrapper"><div class="card"><div class="policy"><div class="left"><i class="rango-exchnage fs40"></i></div> <div class="right"><span class="font-setting fs20">Product Exchange and EMI Available </span></div></div></div></div></div>',
        ];

        Log::info("Info:- prepareVelocityData() created for company " . $companyRepository->domain . ".");

        return $this->velocityMetadataRepository->create($data);
    }

    /**
     * Prepares a default Config data
     */
    public function prepareConfigData()
    {
        $companyRepository = Company::getCurrent();

        $localeRepository = $this->localeRepository->findOneWhere([
            'company_id'    => $companyRepository->id
        ]);

        $data = [
            'channel'   => $companyRepository->username,
            'locale'    => $localeRepository->code,
            'emails'    => [
                'general'   => [
                    'notifications' => [
                        'emails.general.notifications.verification'         => 1,
                        'emails.general.notifications.registration'         => 1,
                        'emails.general.notifications.customer'             => 1,
                        'emails.general.notifications.new-order'            => 1,
                        'emails.general.notifications.new-admin'            => 1,
                        'emails.general.notifications.new-invoice'          => 1,
                        'emails.general.notifications.new-refund'           => 1,
                        'emails.general.notifications.new-shipment'         => 1,
                        'emails.general.notifications.new-inventory-source' => 1,
                        'emails.general.notifications.cancel-order'         => 1,
                    ]
                ]
            ],
            'catalog'   => [
                'products'  => [
                    'guest-checkout'    => [
                        'allow-guest-checkout'  => 1,
                    ],
                    'review'            => [
                        'guest_review'          => 0,
                    ],
                ]
            ],
            'general'  => [
                'general'   => [
                    'email_setting' => [
                        'sender_name'       => 'Bagisto Shop',
                        'shop_email_from'   => $companyRepository->username . '@bagshop.com',
                        'admin_name'        => 'Bagisto Admin',
                        'admin_email'       => $companyRepository->username . '@bagadmin.com',
                    ]
                ],

                'content'   => [
                    'shop' => [
                        'compare_option'    => [
                            'general.content.shop.compare_option'   => 1,
                        ]
                    ]
                ]
            ]
        ];
        
        Log::info("Info:- prepareConfigData() created for company " . $companyRepository->domain . ".");

        return $this->coreConfigRepository->create($data);
    }

    /**
     * It will store a check in the companies
     * that all the necessary data had been
     * inserted successfully or not
     *
     */
    public function setInstallationCompleteParam()
    {
        $companyRepository = Company::getCurrent();

        $companyRepository->more_info = json_encode([
            'company_created'   => true,
            'seeded'            => true
        ]);

        $companyRepository->save();

        Log::info("Info:- setInstallationCompleteParam() complated for company " . $companyRepository->domain . ".");

        return $companyRepository;
    }
}