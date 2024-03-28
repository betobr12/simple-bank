<?php

namespace App\Modules\Transaction\Services\Contracts;

interface CellRechargeTransactionServiceInterface
{
    /**
     * @param object $data
     */
    public function handler(object $data);

}
