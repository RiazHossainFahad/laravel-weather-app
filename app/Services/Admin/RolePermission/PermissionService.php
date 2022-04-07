<?php

namespace App\Services\Admin\RolePermission;

use App\Services\BaseService;
use App\Models\RolePermission\Permission;

class PermissionService extends BaseService
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    public function getAllParentPermissions($with)
    {
        try {
            return $this->model->with($with)->where('parent_id', null)->get();
        } catch (\Throwable $th) {
            return [];
        }
    }
}