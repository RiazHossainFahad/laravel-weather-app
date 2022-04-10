<?php

namespace App\Services\Admin\User;

use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function updateOrCreateUser($data_array, $id = null)
    {
        try {
            DB::beginTransaction();
            $data = collect($data_array)->only([
                'name', 'email'
            ])->toArray();

            if (array_key_exists('password', $data_array) && $data_array['password']) {
                $data['password'] = Hash::make($data_array['password']);
            }

            $model_data = $this->model->updateOrCreate(['id' => $id], $data);

            if ($data_array['roles']) {
                $model_data->syncRoles($data_array['roles']);
            }

            DB::commit();

            return $model_data;
        } catch (\Throwable $th) {
            DB::rollback();

            throw $th;
        }
    }

    public function deleteUser($id)
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