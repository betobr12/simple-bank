<?php

namespace App\Modules\Card\Services\Contracts;

interface CardServiceInterface
{
    /**
     * @param object $data
     */
    public function handler(object $data);

}
