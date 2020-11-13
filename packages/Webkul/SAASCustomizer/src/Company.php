<?php

namespace Webkul\SAASCustomizer;

use Webkul\SAASCustomizer\Repositories\Super\CompanyRepository;
use Webkul\SAASCustomizer\Repositories\Super\CurrencyRepository;
use Webkul\SAASCustomizer\Repositories\Super\CurrencyExchangeRateRepository;
use Webkul\SAASCustomizer\Repositories\Super\LocaleRepository;
use Webkul\SAASCustomizer\Repositories\Super\ChannelRepository;
use Webkul\Core\Repositories\ChannelRepository as BaseChannelRepository;
use Webkul\SAASCustomizer\Repositories\Super\SuperConfigRepository;
use Webkul\SAASCustomizer\Contracts\Channel;
use Illuminate\Support\Facades\Config;
use Exception;

class Company
{
    /**
     * CompanyRepository class
     *
     * @var mixed
     */
    protected $companyRepository;

    /**
     * Holds the currently request server name variable
     */
    protected $domain;
        
    /**
     * LocaleRepository class
     *
     * @var mixed
     */
    protected $localeRepository;

    /**
     * CurrencyRepository class
     *
     * @var mixed
     */
    protected $currencyRepository;

    /**
     * CurrencyExchangeRateRepository class
     *
     * @var mixed
     */
    protected $currencyExchangeRateRepository;

    /**
     * SuperConfigRepository class
     *
     * @var mixed
     */
    protected $superConfigRepository;

    /**
     * BaseChannelRepository class
     *
     * @var mixed
     */
    protected $baseChannelRepository;

    /**
     * ChannelRepository class
     *
     * @var mixed
     */
    protected $channelRepository;

    public function __construct(
        CompanyRepository $companyRepository,
        LocaleRepository $localeRepository,
        CurrencyRepository $currencyRepository,
        CurrencyExchangeRateRepository $currencyExchangeRateRepository,
        SuperConfigRepository $superConfigRepository,
        ChannelRepository $channelRepository,
        BaseChannelRepository $baseChannelRepository        
    )   {
        $this->companyRepository = $companyRepository;

        $this->localeRepository = $localeRepository;

        $this->currencyRepository = $currencyRepository;

        $this->currencyExchangeRateRepository = $currencyExchangeRateRepository;

        $this->superConfigRepository = $superConfigRepository;

        $this->channelRepository = $channelRepository;

        $this->baseChannelRepository = $baseChannelRepository;
    }

    public function isAllowed()
    {
        $primaryServerName = config('app.url');

        if (isset($_SERVER['SERVER_NAME']))
            $currentURL = $_SERVER['SERVER_NAME'];
        else
            $currentURL = $primaryServerName;

        $primaryServerNameWithoutProtocol = null;

        if (str_contains($primaryServerName, 'http://')) {
            $primaryServerNameWithoutProtocol = explode('http://', $primaryServerName)[1];
        } else if (str_contains($primaryServerName, 'https://')) {
            $primaryServerNameWithoutProtocol = explode('https://', $primaryServerName)[1];
        }

        if (str_contains($primaryServerNameWithoutProtocol, '/')) {
            $primaryServerNameWithoutProtocol = explode('/', $primaryServerNameWithoutProtocol)[0];
        }

        if ($currentURL == $primaryServerNameWithoutProtocol) {
            return true;
        } else {
            return false;
        }
    }

    protected function getAllRegisteredDomains()
    {
        $domains = $this->companyRepository->all();

        return $domains;
    }

    public function getCurrent()
    {
        static $company;

        if (isset($company)) {
            return $company;
        }

        $primaryServerName = config('app.url');

        if (isset($_SERVER['SERVER_NAME']))
            $currentURL = $_SERVER['SERVER_NAME'];
        else
            $currentURL = $primaryServerName;

        if (str_contains($primaryServerName, 'http://')) {
            $primaryServerNameWithoutProtocol = explode('http://', $primaryServerName)[1];
        } else if (str_contains($primaryServerName, 'https://')) {
            $primaryServerNameWithoutProtocol = explode('https://', $primaryServerName)[1];
        }

        if (str_contains($currentURL, 'http://')) {
            $currentURL = explode('http://', $currentURL)[1];
        } else if (str_contains($currentURL, 'http://')) {
            $currentURL = explode('http://', $currentURL)[1];
        }

        if (str_contains($primaryServerNameWithoutProtocol, '/')) {
            $primaryServerNameWithoutProtocol = explode('/', $primaryServerNameWithoutProtocol)[0];
        }

        if ($currentURL == $primaryServerNameWithoutProtocol) {
            $company = 'super-company';

            return $company;
        } else {
            $company = $this->companyRepository->findWhere(['domain' => $currentURL]);

            if ($company->isEmpty()) {
                $cname = explode("www.", $currentURL);
                
                if (count($cname) > 1) {
                    $company = $this->companyRepository->where('cname', $cname)->orWhere('cname', $currentURL)->get();
                } else {
                    $company = $this->companyRepository->findWhere(['cname' => $currentURL]);
                }

                if ($company->isEmpty()) {
                    $baseChannel = $this->baseChannelRepository->findOneByfield('hostname', $currentURL);
                    if ( isset($baseChannel->id) ) {
                        $company = $this->companyRepository->findOrFail($baseChannel->company_id);
                    } else {
                        $path = 'saas';

                        return $this->response($path, 400, trans('saas::app.admin.tenant.exceptions.domain-not-found'), 'domain_not_found');
                    }
                } else {
                    $company = $company->first();

                    if ($company->is_active == 0) {
                        return $this->response($path, 400, trans('saas::app.admin.tenant.exceptions.domain-not-found'), 'company_blocked_by_administrator');
                    }    
                }
            } else {
                $company = $company->first();

                if ($company->is_active == 0) {
                    return $this->response($path, 400, trans('saas::app.admin.tenant.exceptions.domain-not-found'), 'company_blocked_by_administrator');
                }
            }

            return $company;
        }
    }

    private function response($path, $statusCode, $message = null, $type = null)
    {
        if (request()->expectsJson()) {
            return response()->json([
                    'error' => isset($this->jsonErrorMessages[$statusCode])
                        ? $this->jsonErrorMessages[$statusCode]
                        : trans('saas::app.tenant.registration.something-wrong-1')
                ], $statusCode);
        }

        if ($type == null) {
            return response()->view("{$path}::errors.{$statusCode}", ['message' => $message, 'status' => $statusCode], $statusCode);
        } else {
            return response()->view("{$path}::errors.{$type}", ['message' => $message, 'status' => $statusCode], $statusCode);
        }
    }

    /**
     * Returns if there are companies
     * already created
     */
    public function count()
    {
        return $this->companyRepository->findWhere([['id', '>', '0']])->count();
    }

    public function getPrimaryUrl()
    {
        $primaryServerNameWithoutProtocol = null;

        if (str_contains(config('app.url'), 'http://')) {
            $primaryServerNameWithoutProtocol = explode('http://', config('app.url'))[1];
        } else if (str_contains(config('app.url'), 'https://')) {
            $primaryServerNameWithoutProtocol = explode('https://', config('app.url'))[1];
        }
        
        return $primaryServerNameWithoutProtocol;
    }

    /**
     * Returns all channels
     *
     * @return Collection
     */
    public function getAllChannels()
    {
        static $channels;

        if ($channels)
            return $channels;

        return $channels = $this->channelRepository->all();
    }

    /**
     * Returns all locales
     *
     * @return Collection
     */
    public function getAllLocales()
    {
        static $locales;

        if ($locales)
            return $locales;

        return $locales = $this->localeRepository->all();
    }

    /**
     * Returns current locale
     *
     * @return Object
     */
    public function getCurrentLocale()
    {
        static $locale;

        if ($locale) {
            return $locale;
        }

        $locale = $this->localeRepository->findOneByField('code', app()->getLocale());

        if (! $locale) {
            $locale = $this->localeRepository->findOneByField('code', config('app.fallback_locale'));
        }

        return $locale;
    }

    /**
     * Returns all currencies
     *
     * @return Collection
     */
    public function getAllCurrencies()
    {
        static $currencies;

        if ($currencies)
            return $currencies;

        return $currencies = $this->currencyRepository->all();
    }

    /**
     * Returns Super base channel's currency model
     *
     * @return mixed
     */
    public function getBaseCurrency()
    {
        static $currency;

        if ($currency)
            return $currency;

        $baseCurrency = $this->currencyRepository->findOneByField('code', config('app.currency'));

        if (! $baseCurrency)
            $baseCurrency = $this->currencyRepository->first();

        return $currency = $baseCurrency;
    }

    /**
     * Returns base channel's currency code
     *
     * @return string
     */
    public function getBaseCurrencyCode()
    {
        static $currencyCode;

        if ($currencyCode)
            return $currencyCode;

        return ($currency = $this->getBaseCurrency()) ? $currencyCode = $currency->code : '';
    }

    /**
     * Returns currenct channel models
     *
     * @return mixed
     */
    public function getCurrentChannel()
    {
        static $channel;

        if ($channel)
            return $channel;

        $channel = $this->channelRepository->findWhereIn('hostname', [
            request()->getHttpHost(),
            'http://' . request()->getHttpHost(),
            'https://' . request()->getHttpHost(),
        ])->first();

        if (! $channel)
            $channel = $this->channelRepository->first();

        return $channel;
    }

    /**
     * Returns currenct channel code
     *
     * @return string
     */
    public function getCurrentChannelCode(): string
    {
        static $channelCode;

        if ($channelCode)
            return $channelCode;

        return ($channel = $this->getCurrentChannel()) ? $channelCode = $channel->code : '';
    }

    /**
     * Returns default channel models
     *
     * @return null or Channel
     */
    public function getDefaultChannel(): ?Channel
    {
        static $channel;

        if ($channel) {
            return $channel;
        }

        $channel = $this->channelRepository->findOneByField('code', config('app.channel'));

        if ($channel) {
            return $channel;
        }

        return $channel = $this->channelRepository->first();
    }

    /**
     * Returns the default channel code configured in config/app.php
     *
     * @return string
     */
    public function getDefaultChannelCode(): string
    {
        static $channelCode;

        if ($channelCode) {
            return $channelCode;
        }

        return ($channel = $this->getDefaultChannel()) ? $channelCode = $channel->code : '';
    }

    /**
     * Returns base channel's currency model
     *
     * @return mixed
     */
    public function getChannelBaseCurrency()
    {
        static $currency;

        if ($currency)
            return $currency;

        $currenctChannel = $this->getCurrentChannel();

        return $currency = $currenctChannel->base_currency;
    }

    /**
     * Returns base channel's currency code
     *
     * @return string
     */
    public function getChannelBaseCurrencyCode()
    {
        static $currencyCode;

        if ($currencyCode)
            return $currencyCode;

        return ($currency = $this->getChannelBaseCurrency()) ? $currencyCode = $currency->code : '';
    }

    /**
     * Returns current channel's currency model
     *
     * @return mixed
     */
    public function getCurrentCurrency()
    {
        static $currency;

        if ($currency)
            return $currency;

        if ($currencyCode = session()->get('currency')) {
            if ($currency = $this->currencyRepository->findOneByField('code', $currencyCode))
                return $currency;
        }

        return $currency = $this->getChannelBaseCurrency();
    }

    /**
     * Returns current channel's currency code
     *
     * @return string
     */
    public function getCurrentCurrencyCode()
    {
        static $currencyCode;

        if ($currencyCode)
            return $currencyCode;

        return ($currency = $this->getCurrentCurrency()) ? $currencyCode = $currency->code : '';
    }

    /**
     * @param string $fieldName
     *
     * @return array
     */
    public function getSuperConfigField($fieldName)
    {
        foreach (config('company') as $coreData) {
            if (isset($coreData['fields'])) {
                foreach ($coreData['fields'] as $field) {
                    $name = $coreData['key'] . '.' . $field['name'];

                    if ($name == $fieldName) {
                        return $field;
                    }
                }
            }
        }
    }

    /**
     * Retrieve information from super configuation
     *
     * @param string          $field
     * @param int|string|null $channelId
     *
     * @return mixed
     */
    public function getSuperConfigData($field, $channel = null, $locale = null)
    {
        if (null === $channel) {
            $channel = request()->get('channel') ?: ($this->getCurrentChannelCode() ?: $this->getDefaultChannelCode());
        }

        if (null === $locale) {
            $locale = request()->get('locale') ?: app()->getLocale();
        }

        $fields = $this->getSuperConfigField($field);

        $channel_based = false;
        $locale_based = false;

        if (isset($fields['channel_based']) && $fields['channel_based']) {
            $channel_based = true;
        }

        if (isset($fields['locale_based']) && $fields['locale_based']) {
            $locale_based = true;
        }

        if (isset($fields['channel_based']) && $fields['channel_based']) {
            if (isset($fields['locale_based']) && $fields['locale_based']) {
                $coreConfigValue = $this->superConfigRepository->findOneWhere([
                    'code'         => $field,
                    'channel_code' => $channel,
                    'locale_code'  => $locale,
                ]);
            } else {
                $coreConfigValue = $this->superConfigRepository->findOneWhere([
                    'code'         => $field,
                    'channel_code' => $channel,
                ]);
            }
        } else {
            if (isset($fields['locale_based']) && $fields['locale_based']) {
                $coreConfigValue = $this->superConfigRepository->findOneWhere([
                    'code'        => $field,
                    'locale_code' => $locale,
                ]);
            } else {
                $coreConfigValue = $this->superConfigRepository->findOneWhere([
                    'code' => $field,
                ]);
            }
        }

        if (! $coreConfigValue) {
            $fields = explode(".", $field);
            array_shift($fields);
            $field = implode(".", $fields);

            return Config::get($field);
        }

        return $coreConfigValue->value;
    }

    /**
     * Method to sort through the acl items and put them in order
     *
     * @return void
     */
    public function sortItems($items)
    {
        foreach ($items as &$item) {
            if (count($item['children'])) {
                $item['children'] = $this->sortItems($item['children']);
            }
        }

        usort($items, function ($a, $b) {
            if ($a['sort'] == $b['sort']) {
                return 0;
            }

            return ($a['sort'] < $b['sort']) ? -1 : 1;
        });

        return $this->convertToAssociativeArray($items);
    }

    public function convertToAssociativeArray($items)
    {
        foreach ($items as $key1 => $level1) {
            unset($items[$key1]);
            $items[$level1['key']] = $level1;

            if (count($level1['children'])) {
                foreach ($level1['children'] as $key2 => $level2) {
                    $temp2 = explode('.', $level2['key']);
                    $finalKey2 = end($temp2);
                    unset($items[$level1['key']]['children'][$key2]);
                    $items[$level1['key']]['children'][$finalKey2] = $level2;

                    if (count($level2['children'])) {
                        foreach ($level2['children'] as $key3 => $level3) {
                            $temp3 = explode('.', $level3['key']);
                            $finalKey3 = end($temp3);
                            unset($items[$level1['key']]['children'][$finalKey2]['children'][$key3]);
                            $items[$level1['key']]['children'][$finalKey2]['children'][$finalKey3] = $level3;
                        }
                    }

                }
            }
        }

        return $items;
    }
}