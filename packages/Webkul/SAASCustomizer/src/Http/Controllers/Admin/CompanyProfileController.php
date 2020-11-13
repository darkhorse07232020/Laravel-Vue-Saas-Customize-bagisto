<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Admin;

use Webkul\Admin\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Company;

/**
 * CompanyProfileController
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CompanyProfileController extends Controller
{
    protected $_config;

    /**
     * SuperChannelRepository instance
     */
    protected $companyDetails;

    public function __construct()
    {
        $this->_config = request('_config');

        $this->middleware('auth:admin');
    }

    /**
     * To load the company profile index view
     */
    public function index()
    {
        $company = Company::getCurrent();

        $details = $company->details;

        return view($this->_config['view'], compact('company', 'details'));
    }

    /**
     * To update the company profile details
     *
     * @return Response Redirect
     */
    public function update()
    {
        $company = Company::getCurrent();

        $companyDetails = $company->details;
        
        $data = request()->all();
        
        $validator =  Validator::make($data, [
            'first_name'    => 'required|string|max:191',
            'last_name'     => 'required|string|max:191',
            'email'         => 'required|email|max:191|unique:company_personal_details,email,' . $companyDetails['id'],
            'skype'         => 'string|min:6|max:32',
            'cname'         => 'string|unique:companies,cname,' . $company->id,
            'phone'         => 'required|string'
        ]);
        
        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();

            foreach($errors as $key => $error) {
                session()->flash('error', trans($error[0]));

                return redirect()->route($this->_config['redirect']);
            }
        }
        if (! $data['cname']) {
            $data['cname'] = null;
        }
        if ( $companyDetails->update($data) ) {
            
            if ( isset($data['cname']) && $data['cname'] ) {
                $company->cname = $data['cname'];
                $company->save();
            }

            session()->flash('success', trans('saas::app.admin.tenant.update-success', ['resource' => 'Company profile']));
        } else {
            session()->flash('error', trans('saas::app.admin.tenant.update-failed', ['resource' => 'Company profile']));
        }

        return redirect()->route($this->_config['redirect']);
    }
}