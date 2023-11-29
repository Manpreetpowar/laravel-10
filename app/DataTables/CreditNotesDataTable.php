<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Repositories\CreditNoteRepository;

class CreditNotesDataTable extends DataTable
{

    protected $creditNoteRepo;


    public function __construct(CreditNoteRepository $creditNoteRepo){
        $this->creditNoteRepo = $creditNoteRepo;
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
                return view('pages.credit-notes.components.action',['note'=>$data]);
            })
            ->editColumn('amount', function($data) {
                return '$'.$data->amount;
            })
            ->editColumn('created_at', function ($data) {
                return \Carbon\Carbon::parse($data->created_at )->format('d M Y');
            })
            ->editColumn('client.client_name', function ($data) {
                return '<a href="'.route('clients.show',$data->client->id).'">'.$data->client->client_name.'</a>';
            })
            ->addColumn('remark', function($data) {
                $html = '<span class="d-none">'.$data->remark.'</span><form id="remarkForm-'.$data->id.'"><input type="hidden" name="_token" value="'.csrf_token().'"><input type="text" class="remark-input form-control" name="remark" value="'.$data->remark.'"'
                .'data-url="'.url('credit-notes/update-remark/'.$data->id.'').'" data-ajax-type="post" data-form-id="remarkForm-'.$data->id.'"></form>';
                return $html;
            })
            ->rawColumns(['action','remark','client.client_name'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        // $model->newQuery();
        return $this->creditNoteRepo->get();
        // return $model->with('roles','profile');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('credit-note-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
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
            Column::computed('DT_RowIndex','S/N')->orderable(false)->addClass('no-sort-icon'),
            Column::computed('note_id','Credit Note No'),
            Column::computed('created_at','Date'),
            Column::computed('client.client_name','Client Name'),
            Column::make('terms'),
            Column::make('amount')
            ->addClass('text-center'),
            Column::computed('remark','Remarks')
                  ->width(200)
                  ->addClass('text-center'),
            Column::computed('action','Manage')
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
