<?php

namespace App\Modules\Transaction\Services\Contracts;

interface TransferTransactionServiceInterface
{
    /**
     * @param object $data
     */
    public function handler(object $data);

}
