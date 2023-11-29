<?php

namespace App\DataTables;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Repositories\ClientRepository;

class ClientsDataTable extends DataTable
{

    protected $clientRepo;

    public function __construct(ClientRepository $clientRepo){
        $this->clientRepo = $clientRepo;
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
                return view('pages.clients.components.action',['client'=>$data]);
            })
            ->editColumn('credit_limit', function($data) {
                return $data->credit_limit ? '$'.$data->credit_limit : 'N/A';
            })
            ->editColumn('outstanding', function($data) {
                return $data->outstanding ? '$'.$data->outstanding : 'N/A';
            })
            ->editColumn('payment_terms', function($data) {
                return config('constants.payment_terms')[$data->payment_terms];
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Client $model): QueryBuilder
    {
        return $this->clientRepo->get();
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
                    // ->orderBy('id','desc')
                    ->selectStyleSingle()
                    ->parameters([
                        'dom' => '<"top"Bf>rt<"bottom"<"left"il><"right"p>>',
                        // 'dom' => 'Bfrtilp',
                        'buttons' => [[
                            'extend' => 'csv',
                            'text' => 'Export',
                            'className' => 'btn btn-primary',
                            'exportOptions' => [
                                'columns' =>':not(.exclude-export)'
                                // 'columns' => [ 8, ':visible', -1 ]
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
            Column::make('client_id'),
            Column::make('client_name'),
            Column::computed('poc_name', 'POC Name'),
            Column::computed('poc_contact', 'POC Contact'),
            Column::make('client_address')->orderable(false),
            Column::make('payment_terms')->orderable(false),
            Column::make('credit_limit'),
            Column::make('outstanding'),
            Column::computed('action', 'Manage')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center exclude-export'),
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
