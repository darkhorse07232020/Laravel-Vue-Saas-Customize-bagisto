<?php

namespace Webkul\SAASCustomizer\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * Tenants DataGrid class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @author Vivek Sharma <viveksh047@webkul.com> @viveksh-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TenantsDataGrid extends DataGrid
{
    protected $index = 'id'; //the column that needs to be treated as index column

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('companies')
                ->select('id')
                ->addSelect('id', 'name', 'domain', 'cname', 'is_active');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('saas::app.super-user.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('saas::app.super-user.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
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
            'index' => 'cname',
            'label' => trans('saas::app.super-user.datagrid.cname'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
            'closure' => true,
            'wrapper' => function ($row) {
                if ( $row->cname ) {
                    return $row->cname;
                } else {
                    return '-';
                }
            }
        ]);

        $this->addColumn([
            'index' => 'is_active',
            'label' => trans('saas::app.super-user.datagrid.status'),
            'type' => 'boolean',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'closure' => true,
            'wrapper' => function ($row) {
                if ($row->is_active ==1) {
                    return '<i class="icon graph-up-icon"></i>';
                } else {
                    return '<i class="icon graph-down-icon"></i>';
                }
            }
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title' => trans('saas::app.super-user.tenants.view'),
            'type' => 'View',
            'method' => 'GET', //use post only for redirects only
            'route' => 'super.tenants.show-stats',
            'icon' => 'icon eye-icon'
        ]);

        $this->addAction([
            'title' => trans('saas::app.super-user.tenants.edit'),
            'type' => 'View',
            'method' => 'GET', //use post only for redirects only
            'route' => 'super.tenants.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'title' => trans('saas::app.super-user.tenants.delete'),
            'method' => 'POST', // other than get request it fires ajax and self refreshes datagrid
            'route' => 'super.tenants.delete',
            'icon' => 'icon trash-icon'
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'delete',
            'action' => route('super.tenants.massdelete'),
            'label' => trans('saas::app.super-user.tenants.mass-delete'),
            'index' => 'admin_name',
            'method' => 'DELETE'
        ]);
    }
}
