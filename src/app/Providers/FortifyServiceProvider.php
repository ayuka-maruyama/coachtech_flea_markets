<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::registerView(function () {
            return view('auth.register');
        });
        config(['fortify.home' => '/email/verify']);

        Fortify::loginView(function () {
            return view('auth.login');
        });

    }
}
