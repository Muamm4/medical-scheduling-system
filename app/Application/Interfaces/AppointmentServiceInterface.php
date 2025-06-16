<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

interface AppointmentServiceInterface
{
    /**
     * Valida os dados do agendamento
     *
     * @param Request $request
     * @return array
     */
    public function validateAppointmentData(Request $request): array;

    /**
     * Verifica se o paciente pode agendar mais consultas
     * (máximo 3 agendamentos simultâneos)
     *
     * @param Patient $patient
     * @return bool
     */
    public function canScheduleMoreAppointments(Patient $patient): bool;

    /**
     * Busca médicos disponíveis na cidade do paciente
     *
     * @param string $city
     * @return array
     */
    public function findAvailableDoctorsByCity(string $city): array;

    /**
     * Busca horários disponíveis para um médico específico
     *
     * @param string $doctorId
     * @param string $date
     * @return array
     */
    public function getAvailableTimeSlots(string $doctorId, string $date): array;
    
    /**
     * Busca todas as disponibilidades de um médico
     *
     * @param string $doctorId
     * @return array
     */
    public function getAllDoctorAvailability(string $doctorId): array;

    /**
     * Cria um novo agendamento
     *
     * @param array $validatedData
     * @return Appointment
     */
    public function createAppointment(array $validatedData): Appointment;

    /**
     * Cancela um agendamento existente
     * (deve ser com no mínimo 12h de antecedência)
     *
     * @param Appointment $appointment
     * @return bool
     * @throws \Exception Se o cancelamento for com menos de 12h de antecedência
     */
    public function cancelAppointment(Appointment $appointment): bool;

    /**
     * Verifica se um agendamento pode ser cancelado
     * (deve ter no mínimo 12h de antecedência)
     *
     * @param Appointment $appointment
     * @return bool
     */
    public function canCancelAppointment(Appointment $appointment): bool;
    
    /**
     * Verifica se já existe um agendamento para o médico na data e horário especificados
     *
     * @param string $doctorId
     * @param string $date
     * @param string $time
     * @return bool
     */
    public function isTimeSlotAvailable(string $doctorId, string $date, string $time): bool;

    /**
     * Registra logs de chamadas externas
     *
     * @param string $action
     * @param array $payload
     * @param mixed $response
     * @param string|null $error
     * @return void
     */
    public function logExternalCall(string $action, array $payload, $response, ?string $error = null): void;
    
    /**
     * Retorna as opções de status para os filtros
     *
     * @return array
     */
    public function getStatusOptions(): array;
    
    /**
     * Gera um relatório CSV dos agendamentos com base nos filtros
     *
     * @param array $filters Filtros opcionais (data, status, médico, etc)
     * @return string Caminho do arquivo CSV gerado
     */
    public function generateCsvReport(array $filters = []): string;
}
