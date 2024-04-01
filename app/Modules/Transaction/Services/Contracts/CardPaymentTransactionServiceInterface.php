<?php

namespace App\Modules\Transaction\Services\Contracts;

interface CardPaymentTransactionServiceInterface
{
    /**
     * @param object $data
     */
    public function handler(object $data);

}
