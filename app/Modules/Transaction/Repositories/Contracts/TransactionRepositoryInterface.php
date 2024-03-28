<?php

namespace App\Modules\Transaction\Repositories\Contracts;

use App\Repository\Eloquent\BaseRepository\Contracts\BaseRepositoryInterface;

interface TransactionRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param object $data
     */
    public function getTransaction(object $data);
    /**
     * @param object $data
     */
    public function get(object $data);

    /**
     * @param object $data
     */
    public function balance(object $data);

}
