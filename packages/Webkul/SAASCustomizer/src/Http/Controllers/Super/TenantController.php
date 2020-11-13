<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Super;

use Webkul\SAASCustomizer\Http\Controllers\Controller;
use Webkul\SAASCustomizer\Repositories\Super\CompanyRepository;
use Webkul\SAASCustomizer\Repositories\Super\CompanyDetailsRepository;
use Webkul\User\Repositories\AdminRepository as Admin;
use Webkul\User\Repositories\RoleRepository as Role;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\SAASCustomizer\Helpers\DataPurger;
use Webkul\SAASCustomizer\Helpers\StatsPurger;

use Company;
use Request;
use Validator;

/**
 * Tenant controller
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TenantController extends Controller
{
    protected $attribute;
    protected $_config;
    protected $details;
    protected $admin;
    protected $role;
    protected $productRepository;
    protected $dataSeed;
    protected $companyStats;

    public function __construct(
        CompanyRepository $company,
        CompanyDetailsRepository $details,
        Admin $admin,
        Role $role,
        ProductRepository $productRepository,
        DataPurger $dataSeed,
        StatsPurger $companyStats
    ) {
        $this->company = $company;

        $this->details = $details;

        $this->admin = $admin;

        $this->role = $role;

        $this->productRepository = $productRepository;

        $this->dataSeed = $dataSeed;

        $this->companyStats = $companyStats;

        $this->_config = request('_config');

        $this->middleware('super-admin')->except(['list']);

        if (! Company::isAllowed()) {
            throw new \Exception('not_allowed_to_visit_this_section', 400);
        }
    }

    public function list()
    {
        return view($this->_config['view']);
    }

    public function showCompanyStats($id)
    {
        $aggregates = $this->companyStats->getAggregates($id);

        $company = $this->company->find($id);

        return view($this->_config['view'])->with('company', [$company, $aggregates]);
    }

    public function create()
    {
        return view($this->_config['view']);
    }

    public function edit($id)
    {
        $company = $this->company->findOrFail($id);

        return view($this->_config['view'])->with('company', $company);
    }

    public function update($id)
    {
        $data = request()->all();

        $validator =  Validator::make($data, [
            'email'     => 'email|max:191|unique:companies,email,'.$id,
            'name'      => 'required|string|max:191|unique:companies,name,'.$id,
            'domain'    => 'required|string|max:191|unique:companies,domain,'.$id,
            'cname'     => 'string|unique:companies,cname,' . $id,
            'is_active' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();

            foreach($errors as $error) {
                session()->flash('error', trans($error[0]));

                return redirect()->back();
            }
        }

        $company = $this->company->findOrFail($id);

        $domain = request()->input('domain') ? request()->input('domain') : null;

        if ($company) {
            if (! $data['cname']) {
                $data['cname'] = null;
            }
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