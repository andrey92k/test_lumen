<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\UserRepositoryInterface;
use App\Traits\ApiResponder;
use App\Traits\ValidationTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use ValidationTrait, ApiResponder;

    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(Request $request)
    {
        try {
            $data = $request->only('email', 'password');

            if ($errorMessage = $this->validateLogin($data)) {
                throw ValidationException::withMessages([
                    'param' => $errorMessage
                ]);
            }

            $user = $this->userRepository->getUserByWhere('email', $data['email']);

            if(!isset($user)){
                throw ValidationException::withMessages([
                    'param' => 'User not found'
                ]);
            }

            if (Hash::check($data['password'], $user->password)) {
                $item['api_token'] = hash('sha256',  Str::random(60));
                $this->userRepository->update($user->id ,$item);

                $result = [
                   'Type' => 'API Key',
                   'Add to' => 'Query Params',
                   'Key' => 'api_token',
                   'Value' => $item['api_token'],
                ];

                return $this->successResponse($result);
            }else{
                throw ValidationException::withMessages([
                    'param' => 'Password error '
                ]);
            }

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_OK);
        }
    }
}
