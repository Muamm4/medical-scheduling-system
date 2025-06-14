<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Patient;
use Exception;
use Illuminate\Http\Request;

interface PatientServiceInterface
{
    /**
     * Valida os dados do paciente
     *
     * @param Request $request
     * @param Patient|null $patient
     * @return array
     */
    public function validatePatientData(Request $request, ?Patient $patient = null): array;

    /**
     * Verifica se o número de responsáveis é válido
     *
     * @param array $responsibles
     * @return bool
     */
    public function validateResponsiblesCount(array $responsibles): bool;

    /**
     * Cria um novo paciente com seus responsáveis
     *
     * @param array $validatedData
     * @return Patient
     * @throws Exception
     */
    public function createPatient(array $validatedData): Patient;

    /**
     * Atualiza um paciente existente e seus responsáveis
     *
     * @param Patient $patient
     * @param array $validatedData
     * @return Patient
     * @throws Exception
     */
    public function updatePatient(Patient $patient, array $validatedData): Patient;

    /**
     * Remove um paciente do sistema
     *
     * @param Patient $patient
     * @return bool
     * @throws Exception
     */
    public function deletePatient(Patient $patient): bool;
}
