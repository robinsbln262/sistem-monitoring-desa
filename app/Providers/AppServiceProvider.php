<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // =====================================================
        // CUSTOM PAGINATION
        // =====================================================

        Paginator::defaultView('vendor.pagination.custom');

        Paginator::defaultSimpleView('vendor.pagination.simple-default');
    }
}