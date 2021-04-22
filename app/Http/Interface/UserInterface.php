<?php


namespace App\Http\Interfaces;


interface MovieInterface
{

    public function login();
    public function getLoginByCompany($request): bool;
    public function createNewToken(string $token);
    public function loginName();
    public function responseToken($request,$valid,$loginValue);
    public function failerResponse();
    public function createUser($request);
    public function logout( );
    public function getAuthUserData();


    ///MOVIE



}
