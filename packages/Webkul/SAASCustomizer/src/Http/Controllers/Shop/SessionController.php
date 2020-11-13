<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Session;

use Webkul\SAASCustomizer\Http\Controllers\Controller;
use Webkul\Customer\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Arr;
use Cookie;
use Company;


/**
 * SessionController
 */
class SessionController extends Controller
{
    protected $_config;

    /**
     * CustomerRepository Object
     */
    protected $customer;

    public function __construct(
       CustomerRepository $customer
    )
    {
        $this->_config = request('_config');

        $this->customer = $customer;
    }

    public function create()
    {
        $this->validate(request(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $customer = $this->customer->findOneWhere(['email' => request()->email]);

        $company = Company::getCurrent();

        if ( isset($customer['company_id']) && ($customer['company_id'] == $company->id)) {

            $credentials =    Arr::add(request(['email', 'password']), 'company_id', $company->id) ;

            $user = auth()->guard('customer')->attempt($credentials);
            
            if (! $user) {
                session()->flash('error', trans('shop::app.customer.login-form.invalid-creds'));
    
                return redirect()->back();
            }

            if (auth()->guard('customer')->user()->status == 0) {
                auth()->guard('customer')->logout();

                session()->flash('warning', trans('shop::app.customer.login-form.not-activated'));

                return redirect()->back();
            }

            if (auth()->guard('customer')->user()->is_verified == 0) {
                session()->flash('info', trans('shop::app.customer.login-form.verify-first'));
    
                Cookie::queue(Cookie::make('enable-resend', 'true', 1));
    
                Cookie::queue(Cookie::make('email-for-resend', request('email'), 1));
    
                auth()->guard('customer')->logout();
    
                return redirect()->back();
            }

            //Event passed to prepare cart after login
            Event::dispatch('customer.after.login', request('email'));

            return redirect()->intended(route($this->_config['redirect']));
        } else {
            session()->flash('error', trans('shop::app.customer.login-form.invalid-creds'));

            return redirect()->back();
        }
    }
}