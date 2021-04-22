<?php

namespace App\Http\Controllers;

use App\Http\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\User;
use Exception;
use External\Bar\Auth\LoginService;
use External\Bar\Exceptions\ServiceUnavailableException;
use External\Baz\Auth\Authenticator;
use External\Baz\Auth\Responses\Success;
use External\Foo\Auth\AuthWS;
use External\Foo\Exceptions\AuthenticationFailedException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{

    public $userRepo;
    /**
     * NOTE I'm using Reposity Design Pattern
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepo = $userRepository;
//        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws ValidationException
     */
    public function login(Request $request)
    {


        $valid = Validator::make($request->all(), [
            $this->userRepo->loginName() => 'required', 'password' => 'required',
        ]);
//dd($valid->errors());
        try {
            $loginValue = $this->userRepo->getLoginByCompany($request);
        } catch (ServiceUnavailableException | AuthenticationFailedException $e) {
            $this->userRepo->failerResponse();
        }

        return  $this->userRepo->responseToken($request,$valid, $loginValue);
    }

    /**
     * Register a User.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $this->userRepo->createUser($request);
    }

    /**
     * Logout
     *
     * @return JsonResponse
     */
    public function logout()
    {
        $this->userRepo->logout();
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function userProfile()
    {
       // dd();
        $this->userRepo->getAuthUserData();
    }
}
