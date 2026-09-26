<?php

namespace Modules\User\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Modules\User\Interfaces\UserRepositoryInterface;
use Modules\User\Repositories\UserRepository;
use Modules\User\Repositories\EmergencyContactRepository;
use Modules\User\Interfaces\EmergencyContactRepositoryInterface;

class UserServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'User';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'user';

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Register the service provider.
     */
   public function register(): void
{
    parent::register();

    $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    $this->app->bind(EmergencyContactRepositoryInterface::class, EmergencyContactRepository::class);
    $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));
}
}