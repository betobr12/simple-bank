<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Card\Repositories\CardRepository;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\Person\Repositories\PersonRepository;
use App\Modules\Account\Repositories\AccountRepository;
use App\Modules\Company\Repositories\CompanyRepository;
use App\Modules\Transaction\Repositories\TransactionRepository;
use App\Modules\Card\Repositories\Contracts\CardRepositoryInterface;
use App\Modules\User\Repositories\Contracts\UserRepositoryInterface;
use App\Modules\CardTransaction\Repositories\CardTransactionRepository;
use App\Modules\Person\Repositories\Contracts\PersonRepositoryInterface;
use App\Modules\Account\Repositories\Contracts\AccountRepositoryInterface;
use App\Modules\Company\Repositories\Contracts\CompanyRepositoryInterface;
use App\Modules\Transaction\Repositories\Contracts\TransactionRepositoryInterface;
use App\Modules\CardTransaction\Repositories\Contracts\CardTransactionRepositoryInterface;

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
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->bind(PersonRepositoryInterface::class, PersonRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(CardRepositoryInterface::class, CardRepository::class);
        $this->app->bind(CardTransactionRepositoryInterface::class, CardTransactionRepository::class);
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
