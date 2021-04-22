<?php

namespace App\Http\Repositories;

use App\User;
use Exception;
use External\Bar\Auth\LoginService;
use External\Bar\Exceptions\ServiceUnavailableException;
use External\Baz\Auth\Authenticator;
use External\Baz\Auth\Responses\Success;
use External\Foo\Auth\AuthWS;
use External\Foo\Exceptions\AuthenticationFailedException;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserRepository
{
    public $user;
    protected  $name = 'name';

    /**
     * responseToken
     *
     * @param  mixed $request
     * @param  mixed $valid
     * @param  mixed $loginValue
     * @return void
     */
    public function responseToken($request,$valid, $loginValue)
    {
        if (!$valid || !$loginValue) {
            return response()->json([
                'status' => 'failure',
            ]);
        }
        $token = JWTAuth::attempt(['name'=>$request['name'],'password'=>$request['password']]);
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return  response()->json([
            'status' => 'success',
            'access_token' => $token,
        ]);
    }

    /**
     *
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    public function createNewToken(string $token)
    {
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
        ]);
    }

    /**
     * loginName
     *
     * @return void
     */
    public function loginName()
    {
        return property_exists($this, 'name') ? $this->name : 'email';
    }
    /**
     * @param $request
     *
     * @return bool
     *
     * @throws AuthenticationFailedException
     * @throws ServiceUnavailableException
     */
    public function getLoginByCompany($request): bool
    {
        $business = substr($request->name, 0, 3);

        switch ($business) {
            case 'FOO':
                try {
                    (new AuthWS())->authenticate($request->name, $request->password);
                    return true;
                } catch (Exception $e) {
                    throw new AuthenticationFailedException('Wrong login data FOO');
                }
            case 'BAR':
                try {
                    return (new LoginService())->login($request->name, $request->password);
                } catch (Exception $e) {
                    throw new ServiceUnavailableException('Wrong login data BAR');
                }
            case 'BAZ':
                $result = (new Authenticator())->auth($request->name, $request->password);
                if ($result instanceof Success) {
                    return true;
                } else {
                    throw new AuthenticationFailedException('Wrong login data BAZ');
                }
        }

        return false;
    }
    public function createUser($request)
    {
        $user = User::created(
            [
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request->password)
            ]
        );

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }
    /**
     * logout
     *
     * @return void
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * failerResponse
     *
     * @return void
     */
    public function failerResponse()
    {
        return response()->json([
            'status' => 'failure',
        ]);
    }

    /**
     * getAuthUserData
     *
     * @return void
     */
    public function getAuthUserData()
    {
        return response()->json(auth()->user());
    }
}
