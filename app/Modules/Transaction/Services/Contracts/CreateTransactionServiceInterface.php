<?php

namespace App\Modules\Transaction\Services\Contracts;

interface CreateTransactionServiceInterface
{
    /**
     * @param object $data
     */
    public function handler(object $data);

}
