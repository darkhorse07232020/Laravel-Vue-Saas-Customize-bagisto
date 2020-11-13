<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Company;

use Webkul\SAASCustomizer\Http\Controllers\Controller;
use Webkul\SAASCustomizer\Helpers\DataPurger;
use Event;
use Company;

/**
 * Purge controller
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class PurgeController extends Controller
{
    protected $dataSeedHelper;

    public function __construct(DataPurger $dataSeedHelper)
    {
        $this->dataSeedHelper = $dataSeedHelper;

        $this->_config = request('_config');
    }

    public function seedDatabase()
    {
        Event::dispatch('saas.company.register.before');

        // need to get executed only first time
        if (Company::count() == 1) {
            try {
                $this->dataSeedHelper->prepareCountryStateData();
            } catch (\Exception $e) {

            }
        }

        $this->dataSeedHelper->prepareChannelData();

        $this->dataSeedHelper->prepareCustomerGroupData();
        
        $this->dataSeedHelper->prepareAttributeData();

        $this->dataSeedHelper->prepareCMSPagesData();

        $this->dataSeedHelper->prepareVelocityData();

        $this->dataSeedHelper->prepareConfigData();

        Event::dispatch('new.company.registered');

        $this->dataSeedHelper->setInstallationCompleteParam();

        Event::dispatch('saas.company.register.after');

        session()->flash('success', trans('saas::app.tenant.registration.store-created'));

        return redirect()->route('shop.home.index');
    }
}