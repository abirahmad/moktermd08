<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Repositories\ResponseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\AuthRepository;

class AuthController extends Controller
{

    public $authRepository;
    public $responseRepository;

    public function __construct(AuthRepository $authRepository, ResponseRepository $responseRepository)
    {
        $this->authRepository = $authRepository;
        $this->responseRepository = $responseRepository;
    }

    public function login(LoginRequest $request)
    {
        if ($this->authRepository->checkIfAuthenticated($request)) {
            $user = $this->authRepository->findUserByEmailAddress($request->email);
            $tokenCreated = $user->createToken('authToken');
            $data = [
                'user' => $user,
                'access_token' => $tokenCreated->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($tokenCreated->token->expires_at)->toDateTimeString()
            ];
            return $this->responseRepository->ResponseSuccess($data, 'Logged in Successfully');
        } else {
            return $this->responseRepository->ResponseError(null, 'Sorry, Invalid Email and Password');
        }
    }

    /**
     * Logout Function
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->responseRepository->ResponseSuccess(null, 'User Logged Out');
    }
}
