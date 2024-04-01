<?php

namespace App\Modules\CardTransaction\Services\Contracts;

interface CardTransactionServiceInterface
{
    /**
     * @param object $data
     */
    public function handler(object $data);

}
