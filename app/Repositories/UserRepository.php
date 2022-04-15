<?php

namespace App\Repositories;

use App\Models\User as GroupEloquent;

class UserRepository extends AbstractEloquentRepository implements UserRepositoryInterface
{
    protected $eloquent = GroupEloquent::class;

    public function create($item)
    {
        return $this->model()->create($item);
    }

    public function update($id, $item)
    {
        return $this->model()->find($id)->update($item);
    }

    public function getUserByWhere($key ,$value)
    {
        return $this->model()->where($key, $value)->first();
    }

    public function updateUserCompany($data, $company_id)
    {
        return $this->model()->whereIn('id' ,$data)->update(
            [
                'company_id' => $company_id
            ]
        );
    }
}
