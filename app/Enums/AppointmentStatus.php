<?php

namespace App\Enums;

enum AppointmentStatus: int
{
    case SCHEDULED = 1;
    case CANCELED = 2;
    case COMPLETED = 3;

    /**
     * Get the label for the status.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::SCHEDULED => trans('appointment_status.scheduled'),
            self::CANCELED => trans('appointment_status.canceled'),
            self::COMPLETED => trans('appointment_status.completed'),
        };
    }

    /**
     * Get all status as an array for select options.
     *
     * @return array
     */
    public static function toArray(): array
    {
        return [
            self::SCHEDULED->value => self::SCHEDULED->label(),
            self::CANCELED->value => self::CANCELED->label(),
            self::COMPLETED->value => self::COMPLETED->label(),
        ];
    }
}
