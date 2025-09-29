<?php

namespace App\Http\Controllers;

use App\Architecture\Services\Interfaces\IUserService;

class UserController extends Controller
{
    public function __construct(
        private readonly IUserService $userService
    )
    {
    }

    public function index()
    {
        return $this->userService->all();
    }
}
