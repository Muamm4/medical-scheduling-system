<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Application\Interfaces\DashboardServiceInterface;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * The dashboard service instance.
     *
     * @var \App\Application\Interfaces\DashboardServiceInterface
     */
    protected $dashboardService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Application\Interfaces\DashboardServiceInterface $dashboardService
     * @return void
     */
    public function __construct(DashboardServiceInterface $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the dashboard with summary statistics.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $stats = $this->dashboardService->getDashboardStats();
        
        return view('dashboard', ['stats' => $stats]);
    }
}
