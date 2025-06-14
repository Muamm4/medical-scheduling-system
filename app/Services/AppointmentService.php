<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\AppointmentServiceInterface;
use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\IntegrationLog;
use App\Models\Patient;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AppointmentService implements AppointmentServiceInterface
{
    /**
     * URL base da API de médicos
     * 
     * @var string
     */
    protected string $doctorsApiUrl;

    /**
     * Construtor do serviço
     */
    public function __construct()
    {
        // Usa host.docker.internal para acessar o host a partir do contêiner Docker
        $this->doctorsApiUrl = config('services.doctors_api.url', 'http://host.docker.internal:3000');
    }

    /**
     * {@inheritdoc}
     */
    public function validateAppointmentData(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|string',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * {@inheritdoc}
     */
    public function canScheduleMoreAppointments(Patient $patient): bool
    {
        // Verifica se o paciente tem menos de 3 agendamentos ativos
        $activeAppointmentsCount = $patient->appointments()
            ->where('status', AppointmentStatus::SCHEDULED)
            ->where('appointment_datetime', '>', Carbon::now())
            ->count();

        return $activeAppointmentsCount < 3;
    }

    /**
     * {@inheritdoc}
     */
    public function findAvailableDoctorsByCity(string $city): array
    {
        try {
            $endpoint = "{$this->doctorsApiUrl}/doctors";
            
            // Não envie o parâmetro city na URL, pois o json-server não suporta filtros complexos
            // Vamos buscar todos os médicos e filtrar no lado do servidor
            $response = Http::get($endpoint);
            $doctors = $response->json();
            
            $this->logExternalCall('findAvailableDoctorsByCity', ['city' => $city], $doctors);
            
            // Filtra os médicos pela cidade
            $filteredDoctors = array_filter($doctors, function ($doctor) use ($city) {
                return strtolower($doctor['cidade'] ?? '') === strtolower($city);
            });
            
            // Reindexar o array para garantir que seja retornado como array sequencial
            return array_values($filteredDoctors);
        } catch (Exception $e) {
            $this->logExternalCall('findAvailableDoctorsByCity', ['city' => $city], [], $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableTimeSlots(string $doctorId, string $date): array
    {
        try {
            $endpoint = "{$this->doctorsApiUrl}/doctors/{$doctorId}";
            $payload = ['date' => $date];
            
            $response = Http::get($endpoint);
            $doctor = $response->json();
            
            $this->logExternalCall('getAvailableTimeSlots', $payload, $doctor);
            
            if (!$doctor) {
                throw new Exception("Médico não encontrado");
            }
            
            // Procura a disponibilidade para a data especificada
            $availability = [];
            foreach ($doctor['disponibilidade'] ?? [] as $slot) {
                if ($slot['data'] === $date) {
                    $availability = $slot['horarios'] ?? [];
                    break;
                }
            }
            
            return $availability;
        } catch (Exception $e) {
            $this->logExternalCall('getAvailableTimeSlots', $payload, [], $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAllDoctorAvailability(string $doctorId): array
    {
        try {
            $endpoint = "{$this->doctorsApiUrl}/doctors/{$doctorId}";
            
            $response = Http::get($endpoint);
            $doctor = $response->json();
            
            $this->logExternalCall('getAllDoctorAvailability', ['doctor_id' => $doctorId], $doctor);
            
            if (!$doctor) {
                throw new Exception("Médico não encontrado");
            }
            
            $rawAvailability = $doctor['disponibilidade'] ?? [];
            
            // Buscar agendamentos existentes para este médico
            $existingAppointments = Appointment::where('doctor_id', $doctorId)
                ->where('status', AppointmentStatus::SCHEDULED)
                ->get();
            
            // Transformar a disponibilidade no formato esperado pelo frontend
            $formattedAvailability = [];
            
            foreach ($rawAvailability as $date => $times) {
                $formattedSlot = [
                    'data' => $date,
                    'horarios' => []
                ];
                
                foreach ($times as $time) {
                    // Verificar se já existe um agendamento para este horário
                    $isOccupied = $existingAppointments->contains(function ($appointment) use ($date, $time) {
                        $appointmentDate = Carbon::parse($appointment->appointment_datetime)->format('Y-m-d');
                        $appointmentTime = Carbon::parse($appointment->appointment_datetime)->format('H:i');
                        
                        return $appointmentDate === $date && $appointmentTime === $time;
                    });
                    
                    // Adicionar o horário com a informação se está ocupado ou não
                    $formattedSlot['horarios'][] = [
                        'time' => $time,
                        'occupied' => $isOccupied
                    ];
                }
                
                $formattedAvailability[] = $formattedSlot;
            }
            
            return $formattedAvailability;
        } catch (Exception $e) {
            $this->logExternalCall('getAllDoctorAvailability', ['doctor_id' => $doctorId], [], $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createAppointment(array $validatedData): Appointment
    {
        try {
            DB::beginTransaction();
            
            // Busca informações do médico
            $doctorId = $validatedData['doctor_id'];
            $doctorResponse = Http::get("{$this->doctorsApiUrl}/doctors/{$doctorId}");
            $doctor = $doctorResponse->json();
            
            if (!$doctor) {
                throw new Exception("Médico não encontrado");
            }
            
            $this->logExternalCall('getDoctorInfo', ['doctor_id' => $doctorId], $doctor);
            
            // Combina data e hora
            $appointmentDateTime = Carbon::parse(
                $validatedData['appointment_date'] . ' ' . $validatedData['appointment_time']
            );
            
            // Cria o agendamento
            $appointment = new Appointment([
                'patient_id' => $validatedData['patient_id'],
                'doctor_name' => $doctor['nome'] ?? 'Não informado',
                'specialty' => $doctor['especialidade'] ?? 'Não informada',
                'appointment_datetime' => $appointmentDateTime,
                'status' => AppointmentStatus::SCHEDULED,
                'notes' => $validatedData['notes'] ?? null,
            ]);
            
            $appointment->save();
            
            DB::commit();
            return $appointment;
        } catch (Exception $e) {
            DB::rollBack();
            $this->logExternalCall('createAppointment', $validatedData, [], $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function cancelAppointment(Appointment $appointment): bool
    {
        if (!$this->canCancelAppointment($appointment)) {
            throw new Exception('Este agendamento não pode ser cancelado. O cancelamento deve ser feito com no mínimo 12 horas de antecedência.');
        }
        
        try {
            DB::beginTransaction();
            
            $appointment->status = AppointmentStatus::CANCELED;
            $appointment->save();
            
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            $this->logExternalCall('cancelAppointment', ['appointment_id' => $appointment->id], [], $e->getMessage());
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canCancelAppointment(Appointment $appointment): bool
    {
        return $appointment->canBeCanceled();
    }
    
    /**
     * {@inheritdoc}
     */
    public function isTimeSlotAvailable(string $doctorId, string $date, string $time): bool
    {
        // Combinar data e hora para criar o datetime completo
        $appointmentDateTime = Carbon::parse("$date $time");
        
        // Verificar se já existe um agendamento para o médico nesta data e horário
        $existingAppointment = Appointment::where('doctor_id', $doctorId)
            ->where('status', AppointmentStatus::SCHEDULED)
            ->whereDate('appointment_datetime', $appointmentDateTime->toDateString())
            ->whereTime('appointment_datetime', $appointmentDateTime->toTimeString())
            ->first();
        
        // Retorna true se o horário estiver disponível (não existe agendamento)
        return $existingAppointment === null;
    }

    /**
     * {@inheritdoc}
     */
    public function logExternalCall(string $action, array $payload, $response, ?string $error = null): void
    {
        $logData = [
            'action' => $action,
            'payload' => $payload,
            'response' => $response,
            'timestamp' => Carbon::now()->toDateTimeString(),
        ];
        
        IntegrationLog::log(
            $action,
            json_encode($payload),
            $response,
            $error
        );
        
        if ($error) {
            $logData['error'] = $error;
            Log::error('API External Call Error', $logData);
        } else {
            Log::info('API External Call', $logData);
        }
    }
}
