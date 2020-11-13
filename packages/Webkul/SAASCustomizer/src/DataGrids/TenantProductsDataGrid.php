<?php

namespace Webkul\SAASCustomizer\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * Tenant's Products DataGrid class
 *
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TenantProductsDataGrid extends DataGrid
{
    protected $sortOrder = 'desc'; //asc or desc

    protected $index = 'product_id';

    protected $itemsPerPage = 10;

    protected $locale = 'all';

    protected $channel = 'all';

    public function __construct()
    {
        parent::__construct();

        $this->locale = request()->get('locale') ?? 'all';
        $this->channel = request()->get('channel') ?? 'all';
    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('product_flat')
        ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
        ->leftJoin('attribute_families', 'products.attribute_family_id', '=', 'attribute_families.id')
        ->leftJoin('product_inventories', 'product_flat.product_id', '=', 'product_inventories.product_id')
        ->leftJoin('companies', 'products.company_id', '=', 'companies.id')
        ->select(
            'product_flat.product_id as product_id',
            'products.sku as product_sku',
            'product_flat.name as product_name',
            'products.type as product_type',
            'product_flat.status',
            'product_flat.price',
            'attribute_families.name as attribute_family',
            'companies.name as company_name', 'companies.domain',
            DB::raw('SUM(' . DB::getTablePrefix() . 'product_inventories.qty) as quantity')
        );

        if ($this->locale !== 'all') {
            $queryBuilder->where('locale', $this->locale);
        }

        if ($this->channel !== 'all') {
            $queryBuilder->where('channel', $this->channel);
        }

        $queryBuilder->groupBy('product_flat.product_id');

        $this->addFilter('product_id', 'product_flat.product_id');
        $this->addFilter('product_name', 'product_flat.name');
        $this->addFilter('product_sku', 'products.sku');
        $this->addFilter('status', 'product_flat.status');
        $this->addFilter('product_type', 'products.type');
        $this->addFilter('attribute_family', 'attribute_families.name');
        $this->addFilter('company_name', 'companies.name');
        $this->addFilter('domain', 'companies.domain');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'product_id',
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
            'index' => 'product_sku',
            'label' => trans('saas::app.super-user.datagrid.sku'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'product_name',
            'label' => trans('saas::app.super-user.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'attribute_family',
            'label' => trans('saas::app.super-user.datagrid.attribute-family'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'product_type',
            'label' => trans('saas::app.super-user.datagrid.type'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true
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
                    return '<span class="badge badge-md badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-md badge-danger">Inactive</span>';
                }
            }
        ]);

        $this->addColumn([
            'index' => 'price',
            'label' => trans('saas::app.super-user.datagrid.price'),
            'type' => 'price',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'quantity',
            'label' => trans('saas::app.super-user.datagrid.qty'),
            'type' => 'number',
            'sortable' => true,
            'searchable' => false,
            'filterable' => false,
            'wrapper' => function($value) {
                if (is_null($value->quantity))
                    return 0;
                else
                    return $value->quantity;
            }
        ]);
    }
}
