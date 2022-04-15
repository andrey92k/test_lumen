<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function create(array $item);
    public function update(int $id, $item);
    public function delete(int $id);
    public function getUserByWhere(string $key, string $value);
    public function updateUserCompany(array $data, int $id);

}
