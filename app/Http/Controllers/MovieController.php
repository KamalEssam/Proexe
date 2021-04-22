<?php

namespace App\Http\Controllers;

use App\Http\Repositories\MovieRepository;
use External\Foo\Auth\AuthWS;
use Exception;
use External\Bar\Exceptions\ServiceUnavailableException as ServiceUnavailableExceptionAlias;
use External\Bar\Movies\MovieService;
use External\Baz\Exceptions\ServiceUnavailableException as ServiceUnavailableExceptionBaz;
use External\Baz\Movies\MovieService as MovieServiceBaz;
use External\Foo\Exceptions\ServiceUnavailableException;
use External\Foo\Movies\MovieService as MovieServiceFoo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{

    public $movieRepo;
    /**
     * NOTE I'm using Reposity Design Pattern
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepo = $movieRepository;
//        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getTitles(Request $request): JsonResponse
    {
        $allTitle = $this->getAllTitle();

        return response()->json($allTitle);
    }

    /**
     * @return JsonResponse
     */
    public function getAllTitle(): JsonResponse
    {
        return response()->json([ implode(', ',  $this->movieRepo->returnValues())]);
    }

    /**
     * @return string[]
     *
     * @throws ServiceUnavailableExceptionAlias
     */
    public function getTitleWithBar(){ $this->movieRepo->getTitleWithBar();}

    /**
     * @return array
     *
     * @throws ServiceUnavailableExceptionBaz
     */
    public function getTitleWithBaz(){ $this->movieRepo->getTitleWithBaz();}
    /**
     * @return string[]
     *
     * @throws ServiceUnavailableException
     */
    public function getTitleWithFoo() { $this->movieRepo->getTitleWithFoo();}
}
