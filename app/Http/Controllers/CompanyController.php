<?php

namespace App\Http\Controllers;

use App\Repositories\CompanyRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    use ApiResponder;

    private $companyRepository, $userRepository;

    public function __construct(CompanyRepositoryInterface $companyRepository, UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->companyRepository = $companyRepository;
    }

    public function index()
    {
        $result = $this->companyRepository->getAll();
        return $this->successResponse($result);
    }

    public function store(Request $request)
    {
        $data = $request->only('title', 'description','phone');
        $validator = Validator::make($data, [
            'title' => 'required|max:255',
            'description' => 'required',
            'phone' => 'required|max:255',
        ]);

        if($validator->fails()){
            return $this->errorResponse($validator->getMessageBag()->toArray(), Response::HTTP_BAD_REQUEST);
        }

        $result = $this->companyRepository->create($data);

        if(isset($request->users)){
            $this->userRepository->updateUserCompany($request->users, $result->id);
        }

        return $this->successResponse($result);
    }
}
