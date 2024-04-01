<?php

namespace App\Libraries;

class Document
{
    /**
     * @var mixed
     */
    public $cpf_cnpj;

    /**
     * @return mixed
     */
    public function validdateCpfOrCnpj()
    {
        if (strlen($this->cpf_cnpj) == 11) {
            return $this->validateCPF();
        }

        if (strlen($this->cpf_cnpj) == 14) {
            return $this->validateCNPJ();
        }
    }

    public function validateCPF()
    {
        $cpf = preg_replace('/[^0-9]/', '', $this->cpf_cnpj);

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 9; ++$i) {
            $sum += $cpf[$i] * (10 - $i);
        }

        $remainder = $sum % 11;
        $first_digit = ($remainder < 2) ? 0 : 11 - $remainder;

        if ($cpf[9] != $first_digit) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 10; ++$i) {
            $sum += $cpf[$i] * (11 - $i);
        }

        $remainder = $sum % 11;
        $second_digit = ($remainder < 2) ? 0 : 11 - $remainder;

        if ($cpf[10] != $second_digit) {
            return false;
        }
        return true;
    }

    public function validateCNPJ()
    {
        $cnpj = preg_replace('/[^0-9]/', '', $this->cpf_cnpj);

        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        $sum = 0;
        $weight = 5;
        for ($i = 0; $i < 12; ++$i) {
            $sum += $cnpj[$i] * $weight;
            $weight = ($weight == 2) ? 9 : $weight - 1;
        }

        $remainder = $sum % 11;
        $first_digit = ($remainder < 2) ? 0 : 11 - $remainder;

        if ($cnpj[12] != $first_digit) {
            return false;
        }

        $sum = 0;
        $weight = 6;
        for ($i = 0; $i < 13; ++$i) {
            $sum += $cnpj[$i] * $weight;
            $weight = ($weight == 2) ? 9 : $weight - 1;
        }

        $remainder = $sum % 11;
        $second_digit = ($remainder < 2) ? 0 : 11 - $remainder;

        if ($cnpj[13] != $second_digit) {
            return false;
        }
        return true;
    }
}
