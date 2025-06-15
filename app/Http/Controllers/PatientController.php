<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\PatientServiceInterface;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PatientController extends Controller
{
    /**
     * O serviço de pacientes que encapsula a lógica de negócio
     *
     * @var PatientServiceInterface
     */
    protected PatientServiceInterface $patientService;

    /**
     * Construtor com injeção de dependência do serviço
     *
     * @param PatientServiceInterface $patientService
     */
    public function __construct(PatientServiceInterface $patientService)
    {
        $this->patientService = $patientService;
    }

    /**
     * Display a listing of the patients.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $query = Patient::query();
        
        // Filtro por nome
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        
        // Filtro por CPF
        if ($request->filled('cpf')) {
            $cpf = preg_replace('/[^0-9]/', '', $request->cpf);
            $query->where('cpf', 'like', '%' . $cpf . '%');
        }
        
        // Filtro por cidade
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }
        
        // Filtro por estado
        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }
        
        // Ordenação
        $query->latest();
        
        $patients = $query->paginate(10)->withQueryString();
        
        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new patient.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('patients.create');
    }

    /**
     * Store a newly created patient in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validar dados do paciente
            $validatedData = $this->patientService->validatePatientData($request);
            
            // Verificar se foram enviados exatamente 2 responsáveis
            if (!$this->patientService->validateResponsiblesCount($validatedData['responsible'] ?? [])) {
                return back()
                    ->withInput()
                    ->withErrors(['responsible' => 'É necessário cadastrar exatamente 2 responsáveis.']);
            }
            
            // Criar paciente com seus responsáveis
            $this->patientService->createPatient($validatedData);
            
            return redirect()->route('patients.index')
                ->with('success', 'Paciente cadastrado com sucesso!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao cadastrar paciente: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified patient.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\View\View
     */
    public function show(Patient $patient): View
    {
        $patient->load('responsibles', 'appointments');
        
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified patient.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\View\View
     */
    public function edit(Patient $patient): View
    {
        $patient->load('responsibles');
        
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Patient $patient): RedirectResponse
    {
        try {
            // Validar dados do paciente
            $validatedData = $this->patientService->validatePatientData($request, $patient);
            
            // Verificar se foram enviados exatamente 2 responsáveis
            if (!$this->patientService->validateResponsiblesCount($validatedData['responsible'] ?? [])) {
                return back()
                    ->withInput()
                    ->withErrors(['responsible' => 'É necessário cadastrar exatamente 2 responsáveis.']);
            }
            
            // Atualizar paciente com seus responsáveis
            $this->patientService->updatePatient($patient, $validatedData);
            
            return redirect()->route('patients.show', $patient)
                ->with('success', 'Paciente atualizado com sucesso!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao atualizar paciente: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified patient from storage.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Patient $patient): RedirectResponse
    {
        try {
            // Delegar a exclusão para o serviço
            $this->patientService->deletePatient($patient);
            
            return redirect()->route('patients.index')
                ->with('success', 'Paciente excluído com sucesso!');
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao excluir paciente: ' . $e->getMessage()]);
        }
    }
}
