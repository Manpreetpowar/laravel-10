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
use App\Repositories\UserRepository;

class UsersDataTable extends DataTable
{

    protected $userRepo;

    public function __construct(UserRepository $userRepo){
        $this->userRepo = $userRepo;
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
                return view('pages.users.components.action',['user'=>$data]);
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        // $model->newQuery();
        return $this->userRepo->get();
        // return $model->with('roles','profile');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('users-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orders([1, 'asc'])
                    //->dom('Bfrtip')
                    // ->orderBy('id','desc')
                    ->selectStyleSingle();
                    // ->buttons([
                    //     Button::make('excel'),
                    //     Button::make('csv'),
                    //     Button::make('pdf'),
                    //     Button::make('print'),
                    //     Button::make('reset'),
                    //     Button::make('reload')
                    // ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex','S/N')->orderable(false)->addClass('no-sort-icon'),
            Column::make('name'),
            Column::computed('roles[0].name','Designation')->orderable(false),
            Column::computed('profile.phone','Contact Number')->orderable(false),
            Column::make('email')->orderable(false),
            Column::computed('profile.em_contact_name','Emergency Contact Name')->orderable(false),
            Column::computed('profile.em_contact_number','Emergency Contact Number')->orderable(false),
            Column::make('status')->addClass('text-capitalize')->orderable(false),
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
