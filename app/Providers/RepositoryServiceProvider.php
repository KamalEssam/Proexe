<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $webInterfacesAndRepositories = [
            'UserInterface' => 'UserRepository',
            'MovieInterface' => 'MovieRepository',

        ];

        foreach ($webInterfacesAndRepositories as $key => $value) {
            $this->app->bind(
                "App\Http\Interfaces\\$key",
                "App\Http\Repositories\\$value"
            );
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
