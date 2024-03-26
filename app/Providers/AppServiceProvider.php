<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Company\Repositories\CompanyRepository;
use App\Modules\Company\Repositories\Contracts\CompanyRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
