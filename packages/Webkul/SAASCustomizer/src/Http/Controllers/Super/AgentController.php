<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Super;

use Webkul\SAASCustomizer\Http\Controllers\Controller;
use Webkul\SAASCustomizer\Repositories\Super\AgentRepository;
use Webkul\SAASCustomizer\Http\Requests\AgentForm;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Event;

/**
 * Agent controller
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AgentController extends Controller
{

    protected $_config;

    /**
     * AgentRepository object
     *
     * @var Object
     */
    protected $agentRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\SAASCustomizer\Repositories\AgentRepository $agentRepository
     * @return void
     */
    public function __construct(
        AgentRepository $agentRepository
    )
    {
        $this->_config = request('_config');

        $this->agentRepository = $agentRepository;

        $this->middleware('auth:super-admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view($this->_config['view']);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Webkul\SAASCustomizer\Http\Requests\AgentForm  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AgentForm $request)
    {
        $data = $request->all();

        if (isset($data['password']) && $data['password']) {
            $data['password'] = bcrypt($data['password']);
            $data['api_token'] = Str::random(80);
        }

        Event::dispatch('super.agent.create.before');

        $agent = $this->agentRepository->create($data);

        Event::dispatch('super.agent.create.after', $agent);

        session()->flash('success', trans('saas::app.super-user.settings.agents.create-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param integer $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $agent = $this->agentRepository->findOrFail($id);

        return view($this->_config['view'], compact('agent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Webkul\SAASCustomizer\Http\Requests\AgentForm  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AgentForm $request, $id)
    {
        $data = $request->all();

        $agent = auth()->guard('super-admin')->user();

        $hashCheck = \Hash::check($data['old_password'], $agent->password);

        if (! $hashCheck) {
            session()->flash('warning', trans('saas::app.super-user.settings.agents.error-password-not-match'));

            return redirect()->back();
        }

        if (! $data['password']) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        if (isset($data['status'])) {
            $data['status'] = 1;
        } else {
            $data['status'] = 0;
        }

        Event::dispatch('super.agent.update.before', $id);

        $agent = $this->agentRepository->update($data, $id);

        Event::dispatch('super.agent.update.after', $agent);

        session()->flash('success', trans('saas::app.super-user.settings.agents.update-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function destroy($id)
    {
        $agent = $this->agentRepository->findOrFail($id);

        if ($this->agentRepository->count() == 1) {
            session()->flash('error', trans('saas::app.super-user.settings.agents.last-delete-error'));
        } else {
            Event::dispatch('super.agent.delete.before', $id);

            if (auth()->guard('super-admin')->user()->id == $id) {
                return response()->json([
                    'redirect' => route('super.agents.confirm', ['id' => $id]),
                ]);
            }

            try {
                $this->agentRepository->delete($id);

                session()->flash('success', trans('saas::app.super-user.settings.agents.delete-success'));

                Event::dispatch('super.agent.delete.after', $id);

                return response()->json(['message' => true], 200);
            } catch (Exception $e) {
                session()->flash('error', trans('saas::app.super-user.settings.agents.delete-failed'));
            }
        }

        return response()->json(['message' => false], 400);
    }

    /**
     * Show the form for confirming the agent password.
     *
     * @param integer $id
     * @return \Illuminate\View\View
     */
    public function confirm($id)
    {
        $agent = $this->agentRepository->findOrFail($id);

        return view($this->_config['view'], compact('agent'));
    }

    /**
     * destroy current after confirming
     *
     * @return mixed
     */
    public function destroySelf()
    {
        $password = request()->input('password');

        if (\Hash::check($password, auth()->guard('super-admin')->user()->password)) {
            if ($this->agentRepository->count() == 1) {
                session()->flash('error', trans('saas::app.super-user.settings.agents.last-delete-error'));
            } else {
                $id = auth()->guard('super-admin')->user()->id;

                Event::dispatch('super.agent.delete.before', $id);

                $this->agentRepository->delete($id);

                auth()->guard('super-admin')->logout();

                Event::dispatch('super.agent.delete.after', $id);

                session()->flash('success', trans('saas::app.super-user.settings.agents.delete-success'));

                return redirect()->route('super.session.index');
            }
        } else {
            session()->flash('warning', trans('saas::app.super-user.settings.agents.incorrect-password'));

            return redirect()->route($this->_config['redirect']);
        }
    }
}