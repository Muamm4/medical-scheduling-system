<?php

namespace App\Helpers;

/**
 * Helper class for formatting data
 */
class FormatHelper
{
    /**
     * Format a CPF number with mask
     *
     * @param string $cpf
     * @return string
     */
    public static function formatCpf(?string $cpf): string
    {
        if (empty($cpf) || strlen($cpf) !== 11) {
            return $cpf ?? '';
        }
        
        return substr($cpf, 0, 3) . '.' . 
               substr($cpf, 3, 3) . '.' . 
               substr($cpf, 6, 3) . '-' . 
               substr($cpf, 9, 2);
    }
    
    /**
     * Format a phone number with mask
     *
     * @param string $phone
     * @return string
     */
    public static function formatPhone(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }
        
        $phone = preg_replace('/[^0-9]/', '', $phone);
        $length = strlen($phone);
        
        if ($length === 11) {
            // Celular com DDD
            return '(' . substr($phone, 0, 2) . ') ' . 
                   substr($phone, 2, 5) . '-' . 
                   substr($phone, 7, 4);
        } elseif ($length === 10) {
            // Telefone fixo com DDD
            return '(' . substr($phone, 0, 2) . ') ' . 
                   substr($phone, 2, 4) . '-' . 
                   substr($phone, 6, 4);
        }
        
        return $phone;
    }
    
    /**
     * Format a CEP number with mask
     *
     * @param string $cep
     * @return string
     */
    public static function formatCep(?string $cep): string
    {
        if (empty($cep) || strlen($cep) !== 8) {
            return $cep ?? '';
        }
        
        return substr($cep, 0, 5) . '-' . substr($cep, 5, 3);
    }
}
