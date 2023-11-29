<?php

namespace App\DataTables;

use App\Models\Product;
use App\Models\Attachment;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Repositories\ProductRepository;

class ProductsDataTable extends DataTable
{

    protected $productRepo;

    public function __construct(ProductRepository $productRepo){
        $this->productRepo = $productRepo;
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
            return view('pages.products.components.action',['product'=>$data]);
        })
        ->addColumn('acc_ppf', function($data) {
            return $data->variants->where('product_option_type','acc')->first()->option_price;
        })
        ->addColumn('hc_ppf', function($data) {
            return $data->variants->where('product_option_type','hc')->first()->option_price;
        })
        ->addColumn('tp_ppf', function($data) {
            return $data->variants->where('product_option_type','tp')->first()->option_price;
        })
        ->editColumn('is_color_matching', function ($data) {
            $html = '<form id="switchForm-'.$data->id.'"><input type="hidden" name="_token" value="'.csrf_token().'">
                    <div class="form-check m-0 p-0">
                        <label for="is_color_matching_'.$data->id.'" class="switch sm">
                            <input class="form-check-input js-ajax" type="checkbox" data-url="'.url('inventories/color-match/status-change/'.$data->id.'').'" data-ajax-type="post" data-form-id="switchForm-'.$data->id.'" id="is_color_matching_'.$data->id.'" name="is_color_matching_'.$data->id.'" value="option1" '.runtimePreChecked2($data->is_color_matching,1).'>
                            <span class="slider round"></span>
                            <span class="label switch-label">'.($data->is_color_matching ? 'Yes' : 'No').'</span>
                        </label>
                    </div></form>';
            return $html;
        })
        ->editColumn('attachments', function ($data) {
            $attachments = $data->attachments;
            return view('pages.products.components.attachment_links', compact('attachments'));
        })
        ->rawColumns(['action','is_color_matching'])
        ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $this->productRepo->get();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('products-table')
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
            // Column::make('name'),
            Column::computed('DT_RowIndex','S/N')->orderable(false)->addClass('no-sort-icon'),
            Column::computed('name', 'Product Name')
            ->searchable(true),
            Column::computed('sku_code', 'Product Code')
            ->searchable(true),
            Column::computed('attachments', 'Product Image'),
            Column::computed('price', 'Standard PPF'),
            Column::computed('acc_ppf', 'ACC PPF'),
            Column::computed('hc_ppf', 'HC PPF'),
            Column::computed('tp_ppf', 'TP PPM'),
            Column::computed('is_color_matching', 'Enable Color-Match?'),
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
