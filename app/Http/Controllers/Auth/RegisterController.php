<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\UserRepositoryInterface;
use App\Traits\SenderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponder;
use App\Traits\ValidationTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    use ValidationTrait, ApiResponder, SenderService;

    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        try {
            $data = $request->all();

            if ($errorMessage = $this->validateRegister($data)) {
                throw ValidationException::withMessages([
                    'param' => $errorMessage
                ]);
            }

            $data['api_token'] = hash('sha256',  Str::random(60));
            $data['password'] = isset($data['password']) ? Hash::make($data['password']) : null;
            $user = $this->userRepository->create($data);

            return $this->successResponse($user, Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_OK);
        }
    }
}
