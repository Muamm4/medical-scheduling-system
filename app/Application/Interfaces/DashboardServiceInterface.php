<?php

namespace App\Application\Interfaces;

interface DashboardServiceInterface
{
    /**
     * Get dashboard statistics.
     *
     * @return array
     */
    public function getDashboardStats(): array;
}
