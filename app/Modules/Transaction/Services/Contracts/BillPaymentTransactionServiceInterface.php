<?php

namespace App\Modules\Transaction\Services\Contracts;

interface BillPaymentTransactionServiceInterface
{
    /**
     * @param object $data
     */
    public function handler(object $data);

}
