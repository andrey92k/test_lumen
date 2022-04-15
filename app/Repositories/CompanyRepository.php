<?php

namespace App\Repositories;

use App\Models\Company as GroupEloquent;

class CompanyRepository extends AbstractEloquentRepository implements CompanyRepositoryInterface
{
    protected $eloquent = GroupEloquent::class;

    public function getAll()
    {
        return $this->model()->with('users')->get();
    }

    public function create($item)
    {
       return $item = $this->model()->create($item);
    }
}
