<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\SchoolInfo;
use App\UserHasBatch;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        Builder::defaultStringLength(191);

        // count school user admin 
        // composer('*') => for all file 
        // composer('main.*') for main related file
        // composer('main.main.header') for specific section or file load
        view()->composer('main.main.header', function ($view) use ($request){
            $view->with('total_school', SchoolInfo::totalSchool($request));
        });
        view()->composer('backend.main.header', function ($view) use ($request){
            $view->with('batchess', UserHasBatch::getBatch($request));
        });
    }
}
