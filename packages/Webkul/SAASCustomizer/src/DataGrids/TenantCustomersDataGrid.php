<?php

namespace Webkul\SAASCustomizer\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * Tenant's Customers DataGrid class
 *
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TenantCustomersDataGrid extends DataGrid
{
    protected $index = 'customer_id'; //the column that needs to be treated as index column

    protected $sortOrder = 'desc'; //asc or desc

    protected $itemsPerPage = 10;

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('customers')
                ->leftJoin('customer_groups', 'customers.customer_group_id', '=', 'customer_groups.id')
                ->leftJoin('companies', 'customers.company_id', '=', 'companies.id')
                ->addSelect('customers.id as customer_id', 'customers.email as customer_email', 'customer_groups.name as customer_group_name', 'customers.phone', 'companies.name as company_name', 'companies.domain', 'status')
                ->addSelect(DB::raw('CONCAT(' . DB::getTablePrefix() . 'customers.first_name, " ", ' . DB::getTablePrefix() . 'customers.last_name) as full_name'));

        $this->addFilter('customer_id', 'customers.id');
        $this->addFilter('full_name', DB::raw('CONCAT(' . DB::getTablePrefix() . 'customers.first_name, " ", ' . DB::getTablePrefix() . 'customers.last_name)'));
        $this->addFilter('customer_email', 'customers.email');
        $this->addFilter('phone', 'customers.phone');
        $this->addFilter('company_name', 'companies.name');
        $this->addFilter('domain', 'companies.domain');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'customer_id',
            'label' => trans('saas::app.super-user.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'domain',
            'label' => trans('saas::app.super-user.datagrid.domain'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'full_name',
            'label' => trans('saas::app.super-user.datagrid.customer-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'customer_email',
            'label' => trans('saas::app.super-user.datagrid.email'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'customer_group_name',
            'label' => trans('saas::app.super-user.datagrid.group'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'phone',
            'label' => trans('saas::app.super-user.datagrid.phone'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => false,
            'filterable' => false,
            'closure' => true,
            'wrapper' => function ($row) {
                if (! $row->phone)
                    return '-';
                else
                    return $row->phone;
            }
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('saas::app.super-user.datagrid.status'),
            'type' => 'boolean',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'closure' => true,
            'wrapper' => function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge badge-md badge-success">Activated</span>';
                } else {
                    return '<span class="badge badge-md badge-danger">Blocked</span>';
                }
            }
        ]);
    }
}
