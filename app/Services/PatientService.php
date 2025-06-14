<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PatientServiceInterface;
use App\Models\Patient;
use App\Models\Responsible;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PatientService implements PatientServiceInterface
{
    /**
     * Valida os dados do paciente
     *
     * @param Request $request
     * @param Patient|null $patient
     * @return array
     * @throws ValidationException
     */
    public function validatePatientData(Request $request, ?Patient $patient = null): array
    {
        $cpfRule = 'required|string|size:11|unique:patients,cpf';
        
        if ($patient) {
            $cpfRule .= ',' . $patient->id;
        }
        
        return $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => $cpfRule,
            'birth_date' => 'required|date|before:today',
            'zip_code' => 'required|string|size:8',
            'street' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|size:2',
            
            // Responsáveis (2 obrigatórios)
            'responsible.*.id' => 'nullable|exists:responsibles,id',
            'responsible.*.name' => 'required|string|max:255',
            'responsible.*.cpf' => $patient ? 'required|string|size:11' : 'required|string|size:11|unique:responsibles,cpf',
            'responsible.*.relationship' => 'required|string|max:100',
        ]);
    }

    /**
     * Verifica se o número de responsáveis é válido
     *
     * @param array $responsibles
     * @return bool
     */
    public function validateResponsiblesCount(array $responsibles): bool
    {
        return count($responsibles) === 2;
    }

    /**
     * Cria um novo paciente com seus responsáveis
     *
     * @param array $validatedData
     * @return Patient
     * @throws Exception
     */
    public function createPatient(array $validatedData): Patient
    {
        try {
            DB::beginTransaction();
            
            // Criar paciente
            $patient = Patient::create([
                'name' => $validatedData['name'],
                'cpf' => $validatedData['cpf'],
                'birth_date' => $validatedData['birth_date'],
                'zip_code' => $validatedData['zip_code'],
                'street' => $validatedData['street'],
                'number' => $validatedData['number'],
                'complement' => $validatedData['complement'],
                'neighborhood' => $validatedData['neighborhood'],
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],
            ]);
            
            // Criar responsáveis
            foreach ($validatedData['responsible'] as $responsibleData) {
                $patient->responsibles()->create([
                    'name' => $responsibleData['name'],
                    'cpf' => $responsibleData['cpf'],
                    'relationship' => $responsibleData['relationship'],
                ]);
            }
            
            DB::commit();
            
            return $patient;
                
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Atualiza um paciente existente e seus responsáveis
     *
     * @param Patient $patient
     * @param array $validatedData
     * @return Patient
     * @throws Exception
     */
    public function updatePatient(Patient $patient, array $validatedData): Patient
    {
        try {
            DB::beginTransaction();
            
            // Atualizar paciente
            $patient->update([
                'name' => $validatedData['name'],
                'cpf' => $validatedData['cpf'],
                'birth_date' => $validatedData['birth_date'],
                'zip_code' => $validatedData['zip_code'],
                'street' => $validatedData['street'],
                'number' => $validatedData['number'],
                'complement' => $validatedData['complement'],
                'neighborhood' => $validatedData['neighborhood'],
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],
            ]);
            
            // Atualizar responsáveis
            foreach ($validatedData['responsible'] as $responsibleData) {
                if (!empty($responsibleData['id'])) {
                    // Atualizar responsável existente
                    $responsible = Responsible::find($responsibleData['id']);
                    if ($responsible && $responsible->patient_id === $patient->id) {
                        $responsible->update([
                            'name' => $responsibleData['name'],
                            'cpf' => $responsibleData['cpf'],
                            'relationship' => $responsibleData['relationship'],
                        ]);
                    }
                } else {
                    // Criar novo responsável
                    $patient->responsibles()->create([
                        'name' => $responsibleData['name'],
                        'cpf' => $responsibleData['cpf'],
                        'relationship' => $responsibleData['relationship'],
                    ]);
                }
            }
            
            DB::commit();
            
            return $patient;
                
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Remove um paciente do sistema
     *
     * @param Patient $patient
     * @return bool
     * @throws Exception
     */
    public function deletePatient(Patient $patient): bool
    {
        try {
            // Usar soft delete para manter histórico
            return $patient->delete();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
