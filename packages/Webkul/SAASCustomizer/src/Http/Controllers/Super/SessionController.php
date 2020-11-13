<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Super;

use Webkul\SAASCustomizer\Http\Controllers\Controller;

/**
 * Session controller
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SessionController extends Controller
{

    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('super-admin')->except(['index','store']);

        $this->_config = request('_config');

        $this->middleware('guest', ['except' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (! auth()->guard('super-admin')->check()) {
            return view($this->_config['view']);
        } else {
            return redirect()->route('super.tenants.index');
        }
    }

    public function store()
    {
        $this->validate(request(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (! auth()->guard('super-admin')->attempt(request(['email', 'password']))) {
            session()->flash('error', trans('saas::app.super-user.settings.agents.sign-in.login-error'));

            return redirect()->route('super.session.index');
        }

        session()->flash('success', trans('saas::app.super-user.settings.agents.sign-in.login-success'));

        return redirect()->route('super.tenants.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        auth()->guard('super-admin')->logout();

        return redirect()->route('super.session.index');
    }
}