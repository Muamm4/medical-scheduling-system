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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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
            $payload = ['city' => $city];
            
            // Não envie o parâmetro city na URL, pois o json-server não suporta filtros complexos
            // Vamos buscar todos os médicos e filtrar no lado do servidor
            $response = Http::get($endpoint);
            
            // Registrar a chamada externa antes de processar a resposta
            $this->logExternalCall('findAvailableDoctorsByCity', $payload, $response);
            
            $doctors = $response->json();
            
            // Filtra os médicos pela cidade
            $filteredDoctors = array_filter($doctors, function ($doctor) use ($city) {
                return strtolower($doctor['cidade'] ?? '') === strtolower($city);
            });
            
            // Reindexar o array para garantir que seja retornado como array sequencial
            return array_values($filteredDoctors);
        } catch (Exception $e) {
            // Registrar o erro na chamada externa
            $this->logExternalCall('findAvailableDoctorsByCity', ['city' => $city], [], $e->getMessage());
            Log::error("Erro ao buscar médicos por cidade: {$e->getMessage()}", [
                'exception' => $e,
                'city' => $city
            ]);
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
            $payload = ['doctor_id' => $doctorId, 'date' => $date];
            
            $response = Http::get($endpoint);
            
            // Registrar a chamada externa com a resposta completa
            $this->logExternalCall('getAvailableTimeSlots', $payload, $response);
            
            $doctor = $response->json();
            
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
            // Registrar o erro na chamada externa com detalhes
            $this->logExternalCall('getAvailableTimeSlots', $payload, [], $e->getMessage());
            
            Log::error("Erro ao buscar horários disponíveis: {$e->getMessage()}", [
                'exception' => $e,
                'doctor_id' => $doctorId,
                'date' => $date
            ]);
            
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
            $payload = ['doctor_id' => $doctorId];
            
            $response = Http::get($endpoint);
            
            // Registrar a chamada externa com a resposta completa
            $this->logExternalCall('getAllDoctorAvailability', $payload, $response);
            
            $doctor = $response->json();
            
            if (!$doctor) {
                throw new Exception("Médico não encontrado");
            }
            
            $rawAvailability = $doctor['disponibilidade'] ?? [];
            
            // Buscar agendamentos existentes para este médico
            $existingAppointments = Appointment::where('doctor_name', $doctor['nome'])
                ->where('status', AppointmentStatus::SCHEDULED)
                ->get();
            
            // Transformar a disponibilidade no formato esperado pelo frontend
            $formattedAvailability = [];
 
            foreach ($rawAvailability as $slot) {
                $date = $slot['data'];
                $formattedSlot = [
                    'data' => $date,
                    'horarios' => []
                ];
                
                foreach ($slot['horarios'] as $time) {
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
            // Registrar o erro na chamada externa com detalhes
            $this->logExternalCall('getAllDoctorAvailability', ['doctor_id' => $doctorId], [], $e->getMessage());
            
            Log::error("Erro ao buscar disponibilidade do médico: {$e->getMessage()}", [
                'exception' => $e,
                'doctor_id' => $doctorId
            ]);
            
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
            $payload = ['doctor_id' => $doctorId];
            $doctorResponse = Http::get("{$this->doctorsApiUrl}/doctors/{$doctorId}");
            
            // Registrar a chamada externa antes de processar a resposta
            $this->logExternalCall('getDoctorInfo', $payload, $doctorResponse);
            
            $doctor = $doctorResponse->json();
            
            if (!$doctor) {
                throw new Exception("Médico não encontrado");
            }
            
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
            
            // Registrar o erro na chamada externa com detalhes
            $this->logExternalCall('createAppointment', $validatedData, [], $e->getMessage());
            
            Log::error("Erro ao criar agendamento: {$e->getMessage()}", [
                'exception' => $e,
                'doctor_id' => $validatedData['doctor_id'] ?? 'unknown',
                'patient_id' => $validatedData['patient_id'] ?? 'unknown',
                'appointment_date' => $validatedData['appointment_date'] ?? 'unknown',
                'appointment_time' => $validatedData['appointment_time'] ?? 'unknown'
            ]);
            
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
            
            // Registrar o erro na chamada externa com detalhes
            $this->logExternalCall('cancelAppointment', ['appointment_id' => $appointment->id], [], $e->getMessage());
            
            Log::error("Erro ao cancelar agendamento: {$e->getMessage()}", [
                'exception' => $e,
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'doctor_name' => $appointment->doctor_name,
                'appointment_datetime' => $appointment->appointment_datetime
            ]);
            
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
        try {
            // Obter os horários disponíveis para o médico na data especificada
            $availableSlots = $this->getAvailableTimeSlots($doctorId, $date);
            
            // Verificar se o horário solicitado está na lista de horários disponíveis
            return in_array($time, $availableSlots);
        } catch (Exception $e) {
            // Registrar o erro no log
            $this->logExternalCall('isTimeSlotAvailable', [
                'doctor_id' => $doctorId,
                'date' => $date,
                'time' => $time
            ], [], $e->getMessage());
            
            Log::error("Erro ao verificar disponibilidade de horário: {$e->getMessage()}", [
                'exception' => $e,
                'doctor_id' => $doctorId,
                'date' => $date,
                'time' => $time
            ]);
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusOptions(): array
    {
        return AppointmentStatus::toArray();
    }
    
    /**
     * {@inheritdoc}
     */
    public function logExternalCall(string $action, array $payload, $response, ?string $error = null): void
    {
        // Extrair informações do endpoint a partir do action
        $endpoint = '';
        $method = 'GET';
        $httpStatus = null;
        
        // Determinar o endpoint e método com base na ação
        if (strpos($action, 'findAvailableDoctorsByCity') !== false) {
            $endpoint = "{$this->doctorsApiUrl}/doctors";
            $method = 'GET';
        } elseif (strpos($action, 'getAvailableTimeSlots') !== false || strpos($action, 'getAllDoctorAvailability') !== false) {
            $doctorId = $payload['doctor_id'] ?? ($payload['doctorId'] ?? 'unknown');
            $endpoint = "{$this->doctorsApiUrl}/doctors/{$doctorId}";
            $method = 'GET';
        } elseif (strpos($action, 'isTimeSlotAvailable') !== false) {
            $doctorId = $payload['doctor_id'] ?? 'unknown';
            $endpoint = "{$this->doctorsApiUrl}/doctors/{$doctorId}";
            $method = 'GET';
        } elseif (strpos($action, 'createAppointment') !== false) {
            $endpoint = "{$this->doctorsApiUrl}/appointments";
            $method = 'POST';
        } elseif (strpos($action, 'cancelAppointment') !== false) {
            $appointmentId = $payload['appointment_id'] ?? 'unknown';
            $endpoint = "{$this->doctorsApiUrl}/appointments/{$appointmentId}";
            $method = 'PUT';
        }
        
        // Determinar o status HTTP com base na resposta
        if (is_object($response) && method_exists($response, 'status')) {
            $httpStatus = $response->status();
        }
        
        // Converter a resposta para array se for um objeto Response
        $responseData = $response;
        if (is_object($response) && method_exists($response, 'json')) {
            $responseData = $response->json();
        }
        
        try {
            // Garantir que os dados estejam no formato correto para o banco de dados
            $logData = [
                'endpoint' => $endpoint,
                'method' => $method,
                'payload' => $payload,
                'response' => $responseData,
                'http_status' => $httpStatus,
                'error' => $error
            ];
            
            // Registrar o log usando o método create do modelo IntegrationLog
            IntegrationLog::create($logData);
            
            // Registrar também no log do Laravel para facilitar o debug
            Log::channel('api')->info("API Call: {$action}", [
                'endpoint' => $endpoint,
                'method' => $method,
                'payload' => json_encode($payload),
                'response' => json_encode($responseData),
                'http_status' => $httpStatus,
                'error' => $error
            ]);
        } catch (\Exception $e) {
            // Se houver erro ao registrar o log, pelo menos garantimos que fique no log do Laravel
            Log::error("Erro ao registrar log de integração: {$e->getMessage()}", [
                'exception' => $e,
                'action' => $action,
                'endpoint' => $endpoint,
                'method' => $method
            ]);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function generateCsvReport(array $filters = []): string
    {
        // Iniciar a consulta base
        $query = Appointment::with(['patient'])
            ->select(
                'appointments.id',
                'patients.name as patient_name',
                'patients.cpf as patient_cpf',
                'appointments.doctor_name',
                'appointments.specialty',
                'appointments.appointment_datetime',
                'appointments.status',
                'appointments.created_at'
            )
            ->join('patients', 'appointments.patient_id', '=', 'patients.id');
        
        // Aplicar filtros se fornecidos
        if (!empty($filters['start_date'])) {
            $query->whereDate('appointment_datetime', '>=', $filters['start_date']);
        }
        
        if (!empty($filters['end_date'])) {
            $query->whereDate('appointment_datetime', '<=', $filters['end_date']);
        }
        
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['doctor'])) {
            $query->where('doctor_name', 'like', '%' . $filters['doctor'] . '%');
        }
        
        if (!empty($filters['specialty'])) {
            $query->where('specialty', 'like', '%' . $filters['specialty'] . '%');
        }
        
        // Ordenar por data de agendamento
        $appointments = $query->orderBy('appointment_datetime', 'desc')->get();
        
        // Definir o nome do arquivo
        $filename = 'agendamentos_' . date('Y-m-d_His') . '_' . Str::random(8) . '.csv';
        $filepath = 'reports/' . $filename;
        
        // Criar o diretório de relatórios se não existir
        if (!Storage::exists('reports')) {
            Storage::makeDirectory('reports');
        }
        
        // Abrir o arquivo para escrita com UTF-8
        $handle = fopen(Storage::path($filepath), 'w');
        
        // Adicionar BOM (Byte Order Mark) UTF-8 no início do arquivo
        fputs($handle, "\xEF\xBB\xBF");
        
        // Configurar o separador e o delimitador para o CSV
        $delimiter = ';'; // Ponto e vírgula como separador
        $enclosure = '"'; // Aspas duplas como delimitador
        
        // Escrever o cabeçalho do CSV
        fputcsv($handle, [
            'ID',
            'Paciente',
            'CPF',
            'Médico',
            'Especialidade',
            'Data/Hora',
            'Status',
            'Criado em'
        ], $delimiter, $enclosure);
        
        // Escrever os dados
        foreach ($appointments as $appointment) {
            // Obter o label do status
            $statusLabel = match($appointment->status->value) {
                AppointmentStatus::SCHEDULED->value => __('appointment_status.scheduled'),
                AppointmentStatus::CANCELED->value => __('appointment_status.canceled'),
                AppointmentStatus::COMPLETED->value => __('appointment_status.completed'),
                default => 'Desconhecido'
            };
            
            fputcsv($handle, [
                $appointment->id,
                $appointment->patient_name,
                $appointment->patient_cpf,
                $appointment->doctor_name,
                $appointment->specialty,
                Carbon::parse($appointment->appointment_datetime)->format('d/m/Y H:i'),
                $statusLabel,
                Carbon::parse($appointment->created_at)->format('d/m/Y H:i'),
            ], $delimiter, $enclosure);
        }
        
        // Fechar o arquivo
        fclose($handle);
        
        return $filepath;
    }
}
