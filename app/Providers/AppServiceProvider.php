<?php

declare(strict_types=1);

namespace App\Providers;

use App\Application\Interfaces\DashboardServiceInterface;
use App\Application\Services\DashboardService;
use App\Application\Interfaces\AppointmentServiceInterface;
use App\Application\Interfaces\PatientServiceInterface;
use App\Services\AppointmentService;
use App\Services\PatientService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DashboardServiceInterface::class, DashboardService::class);
        $this->app->bind(PatientServiceInterface::class, PatientService::class);
        $this->app->bind(AppointmentServiceInterface::class, AppointmentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {   
        
    }
}
