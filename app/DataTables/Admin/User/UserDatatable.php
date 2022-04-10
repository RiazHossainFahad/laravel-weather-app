<?php

namespace App\DataTables\Admin\User;

use App\Models\User;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('name', function ($model) {
                return $model->name . ($model->id == auth()->id() ? "(You)" : '');
            })
            ->editColumn('roles', function ($model) {
                return $model->getRoleNames();
            })
            ->addColumn('action', function ($model) {
                return getDatatableActionButtons(
                    $model,
                    (checkPermission('edit_user') ? true : false),
                    'admin.users.edit',
                    ($model->id != auth()->id() && checkPermission('delete_user') ? true : false),
                    'admin.users.destroy'
                );
            })
            ->rawColumns(['action', 'name', 'roles'])
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->newQuery()->select('users.*')->latest();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '15%', 'printable' => false, 'title' => 'Action'])
            ->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id', 'id')
                ->visible(false)
                ->printable(true)
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->printable(false),
            Column::computed('DT_RowIndex', 'SL#')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->printable(true)
                ->content('')
                ->render(null),
            Column::make('name', 'name')->title('Name'),
            Column::make('email', 'email')->title('Email'),
            Column::make('roles', 'roles')->title('Role'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return "Users" . '-' . date('YmdHis');
    }
}