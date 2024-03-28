<?php

namespace App\Modules\Transaction\Services\Contracts;

interface DepositTransactionServiceInterface
{
    /**
     * @param object $data
     */
    public function handler(object $data);

}
