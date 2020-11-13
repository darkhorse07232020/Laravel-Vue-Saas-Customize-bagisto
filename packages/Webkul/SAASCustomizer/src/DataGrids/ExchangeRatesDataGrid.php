<?php

namespace Webkul\SAASCustomizer\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * ExchangeRateDataGrid Class
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ExchangeRatesDataGrid extends DataGrid
{
    protected $index = 'currency_exch_id';

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('super_currency_exchange_rates as cer')
                            ->addSelect('cer.id as currency_exch_id', 'curr.name', 'cer.rate')->leftJoin('super_currencies as curr', 'cer.target_currency', '=', 'curr.id');

        $this->addFilter('currency_exch_id', 'cer.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'currency_exch_id',
            'label' => trans('saas::app.super-user.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('saas::app.super-user.datagrid.currency-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'rate',
            'label' => trans('saas::app.super-user.datagrid.exch-rate'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'title' => 'Edit Exchange Rate',
            'method' => 'GET', // use GET request only for redirect purposes
            'route' => 'super.exchange_rates.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'title' => 'Delete Exchange Rate',
            'method' => 'POST', // use GET request only for redirect purposes
            'route' => 'super.exchange_rates.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'Exchange Rate']),
            'icon' => 'icon trash-icon'
        ]);
    }
}