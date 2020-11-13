<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Company;

use Webkul\SAASCustomizer\Http\Controllers\Controller;
use Webkul\SAASCustomizer\Repositories\Super\CompanyRepository;
use Webkul\SAASCustomizer\Repositories\Super\CompanyDetailsRepository;
use Webkul\User\Repositories\AdminRepository as Admin;
use Webkul\User\Repositories\RoleRepository as Role;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\SAASCustomizer\Helpers\DataPurger;
use Webkul\SAASCustomizer\Helpers\StatsPurger;
use Webkul\SAASCustomizer\Http\Requests\CompanyRegistrationForm;

use Company;
use Request;
use Validator;

/**
 * Company controller
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CompanyController extends Controller
{

    protected $_config;

    /**
     * CompanyRepository object
     *
     * @var Object
     */
    protected $companyRepository;

    /**
     * CompanyDetailsRepository object
     *
     * @var Object
     */
    protected $companyDetailsRepository;

    /**
     * CompanyRepository object
     *
     * @var Object
     */
    protected $attribute;

    /**
     * CompanyRepository object
     *
     * @var Object
     */
    protected $admin;

    protected $role;

    protected $productRepository;

    protected $dataSeed;

    protected $companyStats;

    public function __construct(
        CompanyRepository $companyRepository,
        CompanyDetailsRepository $companyDetailsRepository,
        Admin $admin,
        Role $role,
        ProductRepository $productRepository,
        DataPurger $dataSeed,
        StatsPurger $companyStats
    ) {
        $this->companyRepository = $companyRepository;

        $this->companyDetailsRepository = $companyDetailsRepository;

        $this->admin = $admin;

        $this->role = $role;

        $this->productRepository = $productRepository;

        $this->dataSeed = $dataSeed;

        $this->companyStats = $companyStats;

        $this->_config = request('_config');

        $this->middleware('auth:super-admin', ['only' => ['edit', 'update']]);

        if (! Company::isAllowed()) {
            throw new \Exception('not_allowed_to_visit_this_section', 400);
        }
    }

    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Webkul\SAASCustomizer\Http\Requests\CompanyRegistrationForm  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    protected function store(CompanyRegistrationForm $request)
    {
        $data = $request->all();

        $validator = Validator::make(Request::all(), [
            'username'  => 'not_in:'.implode(',', config('excluded-sites'))
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'errors'    => $validator->errors()
            ], 403);
        }

        $primaryServerNameWithoutProtocol = company()->getPrimaryUrl();

        $currentURL = $_SERVER['SERVER_NAME'];
        
        // check if tenant domain
        if (substr_count($currentURL, '.') > 1) {
            $primaryServerNameWithoutProtocol = explode('.', $primaryServerNameWithoutProtocol);

            if ($data['username'] != $primaryServerNameWithoutProtocol[0]) {
                // array_unshift($primaryServerNameWithoutProtocol, $data['username']);
                $primaryServerNameWithoutProtocol[0] = $data['username'];             

                $primaryServeNameWithoutProtocol = implode('.', $primaryServerNameWithoutProtocol);

                $temp = explode('/', $primaryServeNameWithoutProtocol);

                $data['domain'] = current($temp);

                $data['url'] = $primaryServeNameWithoutProtocol;
            } else {
                return response()->json([
                    'success'   => false,
                    'errors'    => [trans('saas::app.tenant.custom-errors.same-domain')]
                ], 403);
            }
        } else {
            // check if super admin domain
            $data['domain'] = strtolower($data['username']). '.' . $primaryServerNameWithoutProtocol;
        }

        $validator = Validator::make($data, [
            'domain'    => 'required|unique:companies,domain'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'errors'    => $validator->errors()
            ], 403);
        }

        $data['more_info'] = json_encode([
            'created'   => true,
            'seeded'    => false
        ]);

        $company = $this->companyRepository->create($data);

        if ($company) {
            $data['password']   = bcrypt($data['password']);
            $data['name']       = $data['first_name'] . ' ' . $data['last_name'];
            $data['status']     = 1;

            //creates a new full privilege role when new company is registered
            $role = $this->role->create([
                'name'              => 'Administrator',
                'description'       => 'Administrator role',
                'permission_type'   => 'all',
                'permissions'       => null,
                'company_id'        => $company->id
            ]);

            $data['role_id']    = $role->id;
            $data['company_id'] = $company->id;

            //creates a new full privilege admin with newly created role above
            $this->admin->create($data);

            //creates the personal details record for the company
            $this->companyDetailsRepository->create($data);

            $company_domain = isset($data['url']) ? $data['url'] : $data['domain'];

            $seed_url = 'http://' . $company_domain . '/company/seed-data';
            if (str_contains(config('app.url'), 'http://')) {
                $seed_url = 'http://' . $company_domain . '/company/seed-data';
            } elseif (str_contains(config('app.url'), 'https://')) {
                $seed_url = 'https://' . $company_domain . '/company/seed-data';
            }
            
            return response()->json([
                'success'   => true,
                'redirect'  => $seed_url
            ], 200);
        } else {
            return response()->json([
                'success' => false
            ], 403);
        }
    }

    public function validateStepOne()
    {
        $niceNames = array(
            'email' => 'Email'
        );

        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|unique:admins,email'
        ]);

        $validator->setAttributeNames($niceNames);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 403);
        } else {
            return response()->json([
                'success' => true,
                'errors' => null
            ], 200);
        }
    }

    public function validateStepThree()
    {
        $niceNames = array(
            'username' => 'Username',
            'name' => 'Organization Name'
        );

        $validator = Validator::make(request()->all(), [
            'username' => 'required|alpha_num|min:3|max:64|unique:companies,username',
            'name' => 'required|string|max:191|unique:companies,name'
        ]);

        $validator->setAttributeNames($niceNames);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 403);
        } else {
            return response()->json([
                'success' => true,
                'errors' => null
            ], 200);
        }
    }

    public function edit($id)
    {
        $company = $this->company->findOrFail($id);

        return view($this->_config['view'])->with('company', $company);
    }

    public function update($id)
    {
        $this->validate(request(), [
            'email' => 'email|max:191|unique:companies,email,'.$id,
            'name' => 'required|string|max:191|unique:companies,name,'.$id,
            'domain' => 'required|string|max:191|unique:companies,domain,'.$id,
            'is_active' => 'required|boolean'
        ]);

        $data = request()->all();

        $company = $this->company->findOrFail($id);

        $domain = request()->input('domain') ? request()->input('domain') : null;

        if ($company) {
            $result = $company->update($data);

            $channel = $company->channels->first();

            $channelUpdated = $channel->update([
                'hostname' => strtolower($domain)
            ]);

            if ($result) {
                session()->flash('success', trans('saas::app.tenant.registration.company-updated'));
            } else {
                session()->flash('warning', trans('saas::app.tenant.registration.something-wrong'));
            }
        } else {
            session()->flash('warning', trans('saas::app.tenant.registration.something-wrong'));
        }

        return redirect()->back();
    }

    protected function changeStatus($id)
    {
        $company = $this->company->find($id);

        if ($company->is_active == 0) {
            $company->update([
                'is_active' => 1
            ]);

            session()->flash('success', trans('saas::app.tenant.registration.company-activated'));
        } else {
            $company->update([
                'is_active' => 0
            ]);

            session()->flash('warning', trans('saas::app.tenant.registration.company-deactivated'));
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = $this->company->findOrFail($id);

        try {
            $this->productRepository->deleteWhere(['company_id' => $id]);

            $company = $this->company->delete($id);

            if ( $company ) {
                session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Company']));

                return response()->json(['message' => true], 200);
            }
        } catch(\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Company']));
        }

        return response()->json(['message' => false], 400);
    }

    /**
     * Remove the specified resources from database
     *
     * @return response \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $suppressFlash = false;

        if (request()->isMethod('post')) {
            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $key => $value) {
                $company = $this->company->find($value);

                try {
                    $this->productRepository->deleteWhere(['company_id' => $value]);

                    $this->company->delete($value);
                } catch (\Exception $e) {
                    $suppressFlash = true;

                    continue;
                }
            }

            if (! $suppressFlash)
                session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', ['resource' => 'companies']));
            else
                session()->flash('info', trans('admin::app.datagrid.mass-ops.partial-action', ['resource' => 'companies']));

            return redirect()->back();
        } else {
            session()->flash('error', trans('admin::app.datagrid.mass-ops.method-error'));

            return redirect()->back();
        }
    }
}