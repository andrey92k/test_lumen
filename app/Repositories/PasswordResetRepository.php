<?php

namespace App\Repositories;

use App\Models\PasswordReset as GroupEloquent;

class PasswordResetRepository extends AbstractEloquentRepository implements PasswordResetRepositoryInterface
{
    protected $eloquent = GroupEloquent::class;

    public function updateOrCreate($data)
    {
        return $this->model()->updateOrCreate(
            ['email' => $data['email']],
            ['token' => $data['token']]
        );
    }

    public function checkToken($token, $email)
    {
        return $this->model()->where('token', $token)->where('email', $email)->first();
    }
}
