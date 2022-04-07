<?php

namespace App\DataTables\Admin\RolePermission;

use Yajra\DataTables\Html\Column;
use App\Models\RolePermission\Role;
use Yajra\DataTables\Services\DataTable;

class RoleDatatable extends DataTable
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
            ->addColumn('action', function ($model) {
                return getDatatableActionButtons(
                    $model,
                    (checkPermission('edit_role') ? true : false),
                    'admin.roles.edit',
                    ($model->is_deletable && checkPermission('delete_role') ? true : false),
                    'admin.roles.destroy',
                    (checkPermission('show_role') ? true : false),
                    'admin.roles.show'
                );
            })
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param Role $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Role $model)
    {
        return $model->newQuery()->select('roles.*')->latest();
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
            Column::make('name', 'name')->title('Role Name'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return "Roles" . '-' . date('YmdHis');
    }
}