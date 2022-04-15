<?php

namespace App\Repositories;

interface CompanyRepositoryInterface
{
    public function getAll();
    public function create(array $item);
}
