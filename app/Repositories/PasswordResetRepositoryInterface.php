<?php

namespace App\Repositories;

interface PasswordResetRepositoryInterface
{
    public function updateOrCreate(array $data);
}
