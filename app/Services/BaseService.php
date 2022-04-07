<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class BaseService
{
    public $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function get($id = null, $with = [], $columns = ['*'])
    {
        try {
            if ($id) {
                $data = $this->model->with($with)->find($id, $columns);
                return $data ? $data : null;
            } else {
                return $this->model->with($with)->latest()->get($columns);
            }
        } catch (\Throwable $th) {
            return $id ? null : [];
        }
    }
}