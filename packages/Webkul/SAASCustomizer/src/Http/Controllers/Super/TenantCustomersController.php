<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Super;

use Webkul\SAASCustomizer\Http\Controllers\Controller;

use Company;

/**
 * Tenant's Customers controller
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TenantCustomersController extends Controller
{
    protected $_config;

    public function __construct() {

        $this->_config = request('_config');

        $this->middleware('auth:super-admin');

        if (! Company::isAllowed()) {
            throw new \Exception('not_allowed_to_visit_this_section', 400);
        }
    }

    public function index()
    {
        return view($this->_config['view']);
    }
}