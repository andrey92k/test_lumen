<?php

namespace App\Repositories;

interface PasswordResetRepositoryInterface
{
    public function updateOrCreate(array $data);
    public function checkToken(string $token, string $email);

}
