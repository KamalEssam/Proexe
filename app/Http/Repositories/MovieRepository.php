<?php

namespace App\Http\Repositories;

use App\User;
use Exception;
use External\Bar\Auth\LoginService;


use External\Bar\Exceptions\ServiceUnavailableException as ServiceUnavailableExceptionAlias;
use External\Bar\Movies\MovieService;
use External\Baz\Exceptions\ServiceUnavailableException as ServiceUnavailableExceptionBaz;
use External\Baz\Movies\MovieService as MovieServiceBaz;
use External\Foo\Exceptions\ServiceUnavailableException;
use External\Foo\Movies\MovieService as MovieServiceFoo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class MovieRepository
{
    public $user;

      /**
     * @return array
     *
     * @throws ServiceUnavailableExceptionBaz
     */
    public function getTitleWithBaz()
    {
        $titles = (new MovieServiceBaz())->getTitles();

        return array_values($titles['titles']);
    }
/**
     * @return string[]
     *
     * @throws ServiceUnavailableExceptionAlias
     */
    public function getTitleWithBar()
    {
        $titles = (new MovieService)->getTitles();

        return $this->array_flatten($titles['titles']);
    }
    /**
     * @return string[]
     *
     * @throws ServiceUnavailableException
     */
    public function getTitleWithFoo()
    {
        $titles = (new MovieServiceFoo())->getTitles();
        return array_values($titles);
    }
   public function array_flatten($array)
    {
        if (!is_array($array)) {
            return false;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->array_flatten($value));
            } else {
                $result = array_merge($result, array($value));
            }
        }
        return $result;
    }
    public function returnValues()
    {
        try {
            $titleWithBar = $this->getTitleWithBar();
            $titleWithBaz = $this->getTitleWithBaz();
            $titleWithFoo = $this->getTitleWithFoo();
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failure',
            ]);
        }
      return  $returnValue = $this->array_flatten(array_merge($titleWithBar, $titleWithBaz, $titleWithFoo));

    }
}
