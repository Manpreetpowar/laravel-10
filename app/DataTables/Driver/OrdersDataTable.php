<?php

namespace App\DataTables\Driver;

use App\Models\ServiceOrder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Repositories\ServiceOrderRepository;

class OrdersDataTable extends DataTable
{

    protected $serviceOrderRepo;

    public function __construct(ServiceOrderRepository $serviceOrderRepo){
        $this->serviceOrderRepo = $serviceOrderRepo;
    }


    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function($data) {
                return '<i class="mdi mdi-arrow-right-thick font-xxl"></i>';
            })
            ->editColumn('service_order_id', function($data) {
                return '<h3 class="m-0"><b>'.$data->service_order_id.'</b></h3><p class="m-0">Customer Name: '. $data->client->client_name.'</p><p class="m-0">'.$data->total_pieces.' Pcs</p>';
            })
            ->rawColumns(['action', 'service_order_id'])
            ->orderColumn('service_order_id', false)
            ->orderColumn('action', false)
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ServiceOrder $model): QueryBuilder
    {
        return $this->serviceOrderRepo->get();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('clients-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->ordering(false)
                    // ->orderBy('id','desc')
                    ->selectStyleSingle()
                    ->parameters([
                        'dom' => '<"top"f>rt<"bottom"<"right"p>>',
                        // 'dom' => 'Bfrtilp',
                        'buttons' => [[
                            'extend' => 'csv',
                            'text' => 'Export',
                            'className' => 'btn btn-primary',
                            'exportOptions' => [
                                'columns' => [ 8, ':visible', -1 ]
                            ]
                        ]],
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('service_order_id', '')
                    ->addClass('p-2'),
            Column::computed('action', '')
                  ->exportable(false)
                  ->printable(false)
                  ->width(10)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }

}
