<?php

namespace Webkul\SAASCustomizer\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * UserDataGrid Class
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AgentDataGrid extends DataGrid
{
    protected $index = 'agent_id';

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('super_admins as sa')
                            ->addSelect('sa.id as agent_id', 'sa.first_name', 'sa.last_name', 'sa.email as agent_email', 'sa.status');
                            // ->leftJoin('roles as ro', 'sa.role_id', '=', 'ro.id');

        $this->addFilter('agent_id', 'sa.id');
        $this->addFilter('first_name', 'sa.first_name');
        $this->addFilter('last_name', 'sa.last_name');
        $this->addFilter('agent_email', 'sa.email');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'agent_id',
            'label' => trans('saas::app.super-user.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'first_name',
            'label' => trans('saas::app.super-user.datagrid.first-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'last_name',
            'label' => trans('saas::app.super-user.datagrid.last-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'agent_email',
            'label' => trans('saas::app.super-user.datagrid.email'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('saas::app.super-user.datagrid.status'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
            'closure' => true,
            'wrapper' => function($value) {
                if ($value->status == 1) {
                    return '<span class="badge badge-md badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-md badge-danger">Inactive</span>';
                }
            }
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'title' => 'Edit User',
            'method' => 'GET', // use GET request only for redirect purposes
            'route' => 'super.agents.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'title' => 'Delete User',
            'method' => 'POST', // use GET request only for redirect purposes
            'route' => 'super.agents.delete',
            'icon' => 'icon trash-icon'
        ]);
    }
}