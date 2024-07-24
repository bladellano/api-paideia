<?php

namespace App\Providers;

use App\Validators\CpfValidator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot()
    {
        //Validação CPF
        Validator::resolver(function ($translator, $data, $rules, $messages) {
            return new CpfValidator($translator, $data, $rules, $messages);
        });

        if (env('APP_ENV') !== 'local') 
            URL::forceScheme('https');
    }
}
