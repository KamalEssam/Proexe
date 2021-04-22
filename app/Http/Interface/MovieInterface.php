<?php


namespace App\Http\Interfaces;


interface MovieInterface
{

    public function getTitleWithBaz();
    public function getTitleWithBar();
    public function getTitleWithFoo();

    public function array_flatten($array);
    public function returnValues();


}
