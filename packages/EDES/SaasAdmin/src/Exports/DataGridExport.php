<?php

namespace EDES\SaasAdmin\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DataGridExport implements FromView, ShouldAutoSize
{
    /**
     * DataGrid instance
     *
     * @var mixed
     */
    protected $gridData = [];

    /**
     * Create a new instance.
     *
     * @param mixed DataGrid
     * @return void
     */
    public function __construct($gridData)
    {
        $this->gridData = $gridData;
    }

     /**
     * function to create a blade view for export.
     *
     */
    public function view(): View
    {
        $columns = [];

        foreach($this->gridData as $key => $gridData) {
            $columns = array_keys((array) $gridData);

            break;
        }
        for($i=0; $i<count($columns); $i++) {
            $columns[$i] = str_replace('channel', 'channel-name', str_replace('quantity', 'qty', $columns[$i]));
            $columns[$i] = __('admin::app.datagrid.'.str_replace('product-', '', str_replace('_', '-', $columns[$i])));
        }

        return view('admin::export.temp', [
            'columns' => $columns,
            'records' => $this->gridData,
        ]);
    }
}