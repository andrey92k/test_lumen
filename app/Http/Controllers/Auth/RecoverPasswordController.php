<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\PasswordResetRepositoryInterface;
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

class RecoverPasswordController extends Controller
{
    use ValidationTrait, ApiResponder, SenderService;

    private $userRepository, $passwordResetRepository;

    public function __construct(UserRepositoryInterface $userRepository, PasswordResetRepositoryInterface $passwordResetRepository)
    {
        $this->userRepository = $userRepository;
        $this->passwordResetRepository = $passwordResetRepository;
    }

    public function recoverPasswordSendEmail(Request $request)
    {
        $data = $request->all();

        try {
            if ($errorMessage = $this->validateOnlyEmail($data)) {
                throw ValidationException::withMessages([
                    'param' => $errorMessage
                ]);
            }

            $user = $this->userRepository->getUserByWhere('email', $data['email']);
            if (!isset($user)) {
                throw ValidationException::withMessages([
                    'param' => 'User not found'
                ]);
            }

            $data['token'] = $this->generateToken(15);
            $this->passwordResetRepository->updateOrCreate($data);
            $this->sendTokenEmail($data);

            return $this->successMessage('Success send email', 200);

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_OK);
        }
    }

    public function recoverPasswordLink(Request $request)
    {
        $data = $request->all();
        $check = $this->passwordResetRepository->checkToken($data['token'], $data['email']);
        if (isset($check)) {
            return $this->successMessage('Next step link method PUT ' .  route('update_password_token') . ' Request body ' .'token: ' . $data['token'] . ' email: ' . $data['email'] . ' password: "password"'  , 200);
        } else {
            return $this->successMessage('Error', 200);
        }
    }

    public function updatePasswordWithToken(Request $request)
    {
        $data = $request->all();

        try {
            if ($errorMessage = $this->validatePasswordWithToken($data)) {
                throw ValidationException::withMessages([
                    'param' => $errorMessage
                ]);
            }

            $checkToken = $this->passwordResetRepository->checkToken($data['token'], $data['email']);

            if (isset($checkToken)) {
                $user = $this->userRepository->getUserByWhere('email', $data['email']);
                $item['password'] = Hash::make($data['password']);
                $this->userRepository->update($user->id, $item);
                $this->passwordResetRepository->delete($checkToken->id);
                return $this->successMessage('Success', 200);
            } else {
                return $this->errorMessage('Error', 419);
            }

        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_OK);
        }
    }
}
