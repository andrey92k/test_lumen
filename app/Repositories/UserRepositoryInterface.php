<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function create($item);
    public function delete(int $id);
    public function getUserByWhere(string $key, string $value);
}
