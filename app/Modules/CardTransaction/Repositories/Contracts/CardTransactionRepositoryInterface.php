<?php

namespace App\Modules\CardTransaction\Repositories\Contracts;

use App\Repository\Eloquent\BaseRepository\Contracts\BaseRepositoryInterface;

interface CardTransactionRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param object $data
     */
    public function balance(object $data);
    /**
     * @param object $data
     */
    public function getCardTransactions(object $data);

    /**
     * @param object $data
     */
    public function firstCardTransactions(object $data);

}
