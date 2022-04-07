<?php

namespace App\Services\Admin\RolePermission;

use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use App\Models\RolePermission\Role;

class RoleService extends BaseService
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function updateOrCreateRole($data_array, $id = null)
    {
        try {
            DB::beginTransaction();
            $data = collect($data_array)->only([
                'name'
            ])->toArray();

            if (!$id) {
                $data['created_by'] = auth()->id();
            } else {
                $data['updated_by'] = auth()->id();
            }

            $model_data = $this->model->updateOrCreate(['id' => $id], $data);

            $model_data->syncPermissions($data_array['permissions']);

            DB::commit();

            return $model_data;
        } catch (\Throwable $th) {
            DB::rollback();

            throw $th;
        }
    }

    public function deleteRole($id)
    {
        try {
            DB::beginTransaction();

            $this->model->where('id', $id)->delete();

            DB::commit();

            return null;
        } catch (\Throwable $th) {
            DB::rollback();

            throw $th;
        }
    }
}