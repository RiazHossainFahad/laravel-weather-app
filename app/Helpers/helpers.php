<?php


if (!function_exists('checkPermission')) {
    function checkPermission($permission)
    {
        try {
            return auth()->user()->can($permission) ? true : false;
        } catch (\Throwable $th) {
            return false;
        }
    }
}

if (!function_exists('getDatatableActionButtons')) {
    function getDatatableActionButtons($model, $show_edit = true, $edit_route = null, $show_delete = true, $delete_route = null, $show_view = false, $view_route = null, $column = 'id', $edit_title = null, $delete_title = null, $show_title = null, $show_icon = null)
    {
        $buttons = '';
        if ($show_edit) {
            $edit_title = $edit_title ?: 'Edit';

            $buttons .= '<a href="' . route($edit_route, $model[$column]) . '" class="dropdown-item text-primary"><em class="icon ti ti-edit"></em><span> ' . $edit_title . '</span></a>';
        }
        if ($show_view) {
            $show_title = $show_title ?: 'Show';
            $show_icon = $show_icon ?: "ti-eye";

            $buttons .= '<a href="' . route($view_route, $model[$column]) . '" class="dropdown-item text-info"><em class="icon ti ' . $show_icon . '"></em><span> ' . $show_title . '</span></a>';
        }
        if ($show_delete) {
            $delete_title = $delete_title ?: 'Delete';

            $buttons .= '<a href="javascript:void(0);" onclick="return makeDeleteRequest(event, `' . $model[$column] . '`)" class="dropdown-item text-danger"><em class="icon ti ti-trash"></em><span> ' . $delete_title . '</span></a>
                        <form action="' . route($delete_route, $model[$column]) . '"  id="delete-form-' . $model[$column] . '" method="post">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                        </form>';
        }

        $buttons = '
                <div class="btn-group dropdown-dark">
                    <button type="button" class="btn btn-dark btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Options
                    </button>
                    <div class="dropdown-menu">
                    ' . $buttons . '
                    </div>
                </div>';

        return $buttons;
    }
}