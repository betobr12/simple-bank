<?php

namespace App\Libraries;

class GenerateAccount
{

    /**
     * @var mixed
     */
    public $cpf_cnpj, $id, $date;

    public function accountNumber()
    {
        $date = substr(preg_replace('/[^0-9]/', '', $this->date), 1, 6);
        return (object) ["account_number" => substr($this->cpf_cnpj, 0, 5) . $date, "digit" => $this->id];
    }
}
