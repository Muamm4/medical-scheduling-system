<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\AppointmentServiceInterface;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    /**
     * O serviço de agendamento
     *
     * @var AppointmentServiceInterface
     */
    protected AppointmentServiceInterface $appointmentService;

    /**
     * Cria uma nova instância do controller
     *
     * @param AppointmentServiceInterface $appointmentService
     */
    public function __construct(AppointmentServiceInterface $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Exibe a lista de agendamentos
     *
     * @return View
     */
    public function index(): View
    {
        $appointments = Appointment::with('patient')
            ->orderBy('appointment_datetime')
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Exibe o formulário para criar um novo agendamento
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        $patients = Patient::orderBy('name')->get();
        $selectedPatientId = $request->query('patient_id');
        
        return view('appointments.create', compact('patients', 'selectedPatientId'));
    }

    /**
     * Busca médicos disponíveis na cidade do paciente
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function findDoctors(Request $request): JsonResponse
    {
        try {
            $patientId = $request->input('patient_id');
            $patient = Patient::findOrFail($patientId);
            
            // Verifica se o paciente pode agendar mais consultas
            if (!$this->appointmentService->canScheduleMoreAppointments($patient)) {
                return response()->json([
                    'error' => 'O paciente já possui o número máximo de 3 agendamentos simultâneos.'
                ], 422);
            }
            
            // Log para debug
            Log::info('Buscando médicos para a cidade: ' . $patient->city);
            
            $doctors = $this->appointmentService->findAvailableDoctorsByCity($patient->city);
            
            // Log para debug
            Log::info('Médicos encontrados: ' . count($doctors));
            
            return response()->json([
                'doctors' => $doctors
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar médicos: ' . $e->getMessage(), [
                'patient_id' => $request->input('patient_id'),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Erro ao buscar médicos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Busca horários disponíveis para um médico específico
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTimeSlots(Request $request): JsonResponse
    {
        try {
            $doctorId = $request->input('doctor_id');
            $date = $request->input('date');
            
            $timeSlots = $this->appointmentService->getAvailableTimeSlots($doctorId, $date);
            
            return response()->json([
                'timeSlots' => $timeSlots
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar horários disponíveis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Busca todas as disponibilidades de um médico
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAllDoctorAvailability(Request $request): JsonResponse
    {
        try {
            $doctorId = $request->input('doctor_id');
            
            // Busca todas as disponibilidades do médico
            $availability = $this->appointmentService->getAllDoctorAvailability($doctorId);
            
            return response()->json([
                'availability' => $availability
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar disponibilidades: ' . $e->getMessage(), [
                'doctor_id' => $request->input('doctor_id'),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Erro ao buscar horários disponíveis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Armazena um novo agendamento
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validar dados do agendamento
            $validatedData = $this->appointmentService->validateAppointmentData($request);
            
            // Verificar se o paciente pode agendar mais consultas
            $patient = Patient::findOrFail($validatedData['patient_id']);
            if (!$this->appointmentService->canScheduleMoreAppointments($patient)) {
                return back()
                    ->withInput()
                    ->withErrors(['patient_id' => 'O paciente já possui o número máximo de 3 agendamentos simultâneos.']);
            }
            
            // Verificar se já existe um agendamento para o mesmo médico, data e horário
            if (!$this->appointmentService->isTimeSlotAvailable(
                $validatedData['doctor_id'],
                $validatedData['appointment_date'],
                $validatedData['appointment_time']
            )) {
                return back()
                    ->withInput()
                    ->withErrors(['appointment_time' => 'Já existe um agendamento para este médico nesta data e horário.']);
            }
            
            // Criar o agendamento
            $appointment = $this->appointmentService->createAppointment($validatedData);
            
            return redirect()->route('appointments.show', $appointment->id)
                ->with('success', 'Agendamento criado com sucesso!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao criar agendamento: ' . $e->getMessage()]);
        }
    }

    /**
     * Exibe os detalhes de um agendamento específico
     *
     * @param Appointment $appointment
     * @return View
     */
    public function show(Appointment $appointment): View
    {
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Exibe o formulário para editar um agendamento
     *
     * @param Appointment $appointment
     * @return View
     */
    public function edit(Appointment $appointment): View
    {
        $patients = Patient::orderBy('name')->get();
        return view('appointments.edit', compact('appointment', 'patients'));
    }

    /**
     * Cancela um agendamento existente
     *
     * @param Appointment $appointment
     * @return RedirectResponse
     */
    public function cancel(Appointment $appointment): RedirectResponse
    {
        try {
            if ($this->appointmentService->cancelAppointment($appointment)) {
                return redirect()->route('appointments.show', $appointment->id)
                    ->with('success', 'Agendamento cancelado com sucesso!');
            }
            
            return back()->withErrors(['error' => 'Não foi possível cancelar o agendamento.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao cancelar agendamento: ' . $e->getMessage()]);
        }
    }
}
