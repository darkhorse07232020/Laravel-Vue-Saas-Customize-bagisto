<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Admin;

use Webkul\SAASCustomizer\Http\Controllers\Controller;
use Webkul\User\Repositories\AdminRepository;

use Company;

/**
 * SessionController
 */
class SessionController extends Controller
{
    protected $_config;

    /**
     * AdminRepository Object
     */
    protected $adminRepository;

    public function __construct(
    AdminRepository $adminRepository
    )
    {
        $this->_config = request('_config');

        $this->adminRepository = $adminRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $company = Company::getCurrent();

        $this->validate(request(), [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        $remember = request('remember');

        $admin = $this->adminRepository->findOneWhere(['email' => request()->email]);

        if ( isset($admin['company_id']) && ($admin['company_id'] == $company->id)) {
            if (! auth()->guard('admin')->attempt(request(['email', 'password']), $remember)) {
                session()->flash('error', trans('admin::app.users.users.login-error'));

                return redirect()->back();
            }

            if (auth()->guard('admin')->user()->status == 0) {
                session()->flash('warning', trans('admin::app.users.users.activate-warning'));

                auth()->guard('admin')->logout();

                return redirect()->route('admin.session.create');
            }

            return redirect()->intended(route($this->_config['redirect']));
        } else {
            session()->flash('error', trans('shop::app.customer.login-form.invalid-creds'));

            return redirect()->back();
        }
    }
}