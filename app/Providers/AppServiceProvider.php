<?php

namespace App\Providers;

use App\Interfaces\SingletonInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /** @var array<class-string, class-string[]> $classes */
        $classes = config('classes-interfaces');

        if (!empty($classes[SingletonInterface::class])) {
            foreach ($classes[SingletonInterface::class] as $class) {
                $this->app->singleton($class);
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
