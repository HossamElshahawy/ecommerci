<?php

namespace App\Architecture\Services\Classes\User;

use App\Architecture\Repositories\Interfaces\IUserRepository;
use App\Architecture\Responder\IApiHttpResponder;
use App\Architecture\Services\Interfaces\IUserService;
use App\Http\Resources\UserResource;
use Symfony\Component\HttpFoundation\Response;

abstract class UserService implements IUserService
{
    public function __construct(
        protected readonly IUserRepository   $userRepository,
        protected readonly IApiHttpResponder $apiHttpResponder,
    )
    {
    }

    public function all()
    {
        try {
            $users = $this->userRepository->getAll();
            $data = UserResource::collection($users)->response()->getData(true);
            return $this->apiHttpResponder->sendSuccess($data);
        } catch (\Exception $e) {
            return $this->apiHttpResponder->sendError('Users cannot be loaded'.$e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
