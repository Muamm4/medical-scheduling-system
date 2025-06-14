<?php

namespace App\Application\Services;

use App\Application\Interfaces\DashboardServiceInterface;
use App\Models\Patient;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;

class DashboardService implements DashboardServiceInterface
{
    /**
     * Get dashboard statistics.
     *
     * @return array
     */
    public function getDashboardStats(): array
    {
        return [
            'total_patients' => $this->getTotalPatients(),
            'total_appointments' => $this->getTotalAppointments(),
            'scheduled_appointments' => $this->getScheduledAppointments(),
            'completed_appointments' => $this->getCompletedAppointments(),
            'canceled_appointments' => $this->getCanceledAppointments(),
            'recent_appointments' => $this->getRecentAppointments(),
        ];
    }

    /**
     * Get total number of patients.
     *
     * @return int
     */
    private function getTotalPatients(): int
    {
        return Patient::count();
    }

    /**
     * Get total number of appointments.
     *
     * @return int
     */
    private function getTotalAppointments(): int
    {
        return Appointment::count();
    }

    /**
     * Get number of scheduled appointments.
     *
     * @return int
     */
    private function getScheduledAppointments(): int
    {
        return Appointment::where('status', AppointmentStatus::SCHEDULED)->count();
    }

    /**
     * Get number of completed appointments.
     *
     * @return int
     */
    private function getCompletedAppointments(): int
    {
        return Appointment::where('status', AppointmentStatus::COMPLETED)->count();
    }

    /**
     * Get number of canceled appointments.
     *
     * @return int
     */
    private function getCanceledAppointments(): int
    {
        return Appointment::where('status', AppointmentStatus::CANCELED)->count();
    }

    /**
     * Get recent scheduled appointments.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getRecentAppointments()
    {
        return Appointment::with('patient')
            ->where('status', AppointmentStatus::SCHEDULED)
            ->orderBy('appointment_datetime')
            ->limit(5)
            ->get();
    }
}
