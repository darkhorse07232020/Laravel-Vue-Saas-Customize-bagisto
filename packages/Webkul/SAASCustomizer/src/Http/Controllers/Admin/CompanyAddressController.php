<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Admin;

use Webkul\SAASCustomizer\Http\Controllers\Controller;
use Webkul\SAASCustomizer\Repositories\CompanyAddressRepository;

/**
 * CompanyAddressController
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CompanyAddressController extends Controller
{
    protected $_config;

    /**
     * CompanyAddressRepository instance
     */
    protected $companyAddressRepository;

    public function __construct(CompanyAddressRepository $companyAddressRepository)
    {
        $this->_config = request('_config');

        $this->companyAddressRepository = $companyAddressRepository;

        $this->middleware('auth:admin');
    }

    /**
     * To load the company Address index
     *
     * @return Response View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * To load the company Address create
     *
     * @return Response View
     */
    public function create()
    {
        return view($this->_config['view'], ['defaultCountry' => config('app.default_country')]);
    }

    /**
     * To store company Address
     *
     * @return Response Redirect
     */
    public function store()
    {
        $this->validate(request(), [
            'address1' => 'required|string|max:160',
            'address2' => 'nullable|string|max:160',
            'country' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
            'phone' => 'required|string'
        ]);

        $data = request()->all();

        if ($this->companyAddressRepository->create($data)) {
            session()->flash('success', trans('saas::app.admin.tenant.address.create-success'));
        } else {
            session()->flash('error', trans('saas::app.admin.tenant.create-failed', [
                'attribute' => 'address'
            ]));
        }

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To load the company Address edit
     *
     * @return Response View
     */
    public function edit($id)
    {
        $address = $this->companyAddressRepository->findOrFail($id);

        $defaultCountry = config('app.default_country');

        return view($this->_config['view'])->with(compact('address', 'defaultCountry'));
    }

    /**
     * To update the company address details
     *
     * @param Integer $id
     *
     * @return Response Redirect
     */
    public function update($id)
    {
        $data = request()->all();

        $address = $this->companyAddressRepository->findOrFail($id);

        if ($address->update(array_except($data, ['_token']))) {
            session()->flash('success', trans('saas::app.admin.tenant.address.update-success'));
        } else {
            session()->flash('error', trans('saas::app.admin.tenant.update-failed'));
        }

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To delete company address resource
     *
     * @param Integer $id
     *
     * @return Response JSON
     */
    protected function destroy($id)
    {
        $address = $this->companyAddressRepository->find($id);

        if($address->delete($id)) {
            session()->flash('success', trans('saas::app.admin.tenant.delete-success', ['resource' => 'Company address']));

            return response()->json(['message' => true], 200);
        } else {
            return response()->json(['message' => false], 200);

            session()->flash('error', trans('saas::app.admin.tenant.delete-failed', ['resource' => 'Company address']));
        }
    }
}