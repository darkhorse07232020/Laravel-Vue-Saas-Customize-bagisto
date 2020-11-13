<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Super;

use Illuminate\Support\Facades\Event;
use Webkul\SAASCustomizer\Http\Controllers\Controller;
use Webkul\SAASCustomizer\Repositories\Super\CurrencyExchangeRateRepository;
use Webkul\SAASCustomizer\Repositories\Super\CurrencyRepository;

/**
 * ExchangeRate controller
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ExchangeRateController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * CurrencyExchangeRateRepository instance
     *
     * @var Object
     */
    protected $currencyExchangeRateRepository;

    /**
     * CurrencyRepository object
     *
     * @var Object
     */
    protected $currencyRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\SAASCustomizer\Repositories\Super\CurrencyExchangeRateRepository $currencyExchangeRateRepository
     * @param  \Webkul\SAASCustomizer\Repositories\Super\CurrencyRepository     $currencyRepository
     * @return void
     */
    public function __construct(
        CurrencyExchangeRateRepository $currencyExchangeRateRepository,
        CurrencyRepository $currencyRepository
    )
    {
        $this->currencyExchangeRateRepository = $currencyExchangeRateRepository;

        $this->currencyRepository = $currencyRepository;

        $this->currencyExchangeRateRepository = $currencyExchangeRateRepository;

        $this->_config = request('_config');
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
        $currencies = $this->currencyRepository->with('CurrencyExchangeRate')->all();

        return view($this->_config['view'], compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'target_currency' => ['required', 'unique:super_currency_exchange_rates,target_currency'],
            'rate' => 'required|numeric'
        ]);

        Event::dispatch('super.exchange_rate.create.before');

        $exchangeRate = $this->currencyExchangeRateRepository->create(request()->all());

        Event::dispatch('super.exchange_rate.create.after', $exchangeRate);

        session()->flash('success', trans('saas::app.super-user.settings.exchange-rates.create-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $currencies = $this->currencyRepository->all();

        $exchangeRate = $this->currencyExchangeRateRepository->findOrFail($id);

        return view($this->_config['view'], compact('currencies', 'exchangeRate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'target_currency' => ['required', 'unique:super_currency_exchange_rates,target_currency,' . $id],
            'rate' => 'required|numeric'
        ]);

        Event::dispatch('super.exchange_rate.update.before', $id);

        $exchangeRate = $this->currencyExchangeRateRepository->update(request()->all(), $id);

        Event::dispatch('super.exchange_rate.update.after', $exchangeRate);

        session()->flash('success', trans('saas::app.super-user.settings.exchange-rates.update-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exchangeRate = $this->currencyExchangeRateRepository->findOrFail($id);

        if ($this->currencyExchangeRateRepository->count() == 1) {
            session()->flash('error', trans('saas::app.super-user.settings.exchange-rates.last-delete-error'));
        } else {
            try {
                Event::dispatch('super.exchange_rate.delete.before', $id);

                $this->currencyExchangeRateRepository->delete($id);

                session()->flash('success', trans('saas::app.super-user.settings.exchange-rates.delete-success'));

                Event::dispatch('super.exchange_rate.delete.after', $id);

                return response()->json(['message' => true], 200);
            } catch (\Exception $e) {
                report($e);
                session()->flash('error', trans('saas::app.response.delete-failed', ['name' => 'Currency Exchange Rate']));
            }
        }

        return response()->json(['message' => false], 400);
    }
}