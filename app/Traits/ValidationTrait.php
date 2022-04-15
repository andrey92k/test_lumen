<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait ValidationTrait
{
    public function validateRegister($data)
    {
        $validator = Validator::make($data, [
            'email' => 'email|required|unique:users|max:255',
            'password' => 'required|max:255',
        ]);

        if($validator->fails()){
            return [
                'error' => $validator->getMessageBag()->toArray()
            ];
        }

        return false;
    }

    public function validateLogin($data)
    {
        $validator = Validator::make($data, [
            'email' => 'email|required|max:255',
            'password' => 'required|max:255',
        ]);

        if($validator->fails()){
            return [
                'error' => $validator->getMessageBag()->toArray()
            ];
        }

        return false;
    }

    public function validateOnlyEmail($data)
    {
        $validator = Validator::make($data, [
            'email' => 'email|required|max:255',
        ]);

        if($validator->fails()){
            return [
                'error' => $validator->getMessageBag()->toArray()
            ];
        }

        return false;
    }

    public function validatePasswordWithToken($data)
    {
        $validator = Validator::make($data, [
            'password' => 'required|min:4',
            'email' => 'email|required|max:255',
            'token' => 'required'
        ]);

        if($validator->fails()){
            return [
                'error' => $validator->getMessageBag()->toArray()
            ];
        }

        return false;
    }

}
