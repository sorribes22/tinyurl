<?php

namespace App\Providers;

use App\Utils\BracketsChecker\BracketsChecker;
use App\Utils\BracketsChecker\StackAutomataBracketsChecker;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BracketsChecker::class, StackAutomataBracketsChecker::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
