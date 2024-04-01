<?php

namespace App\Modules\Account\Repositories\Contracts;

use App\Repository\Eloquent\BaseRepository\Contracts\BaseRepositoryInterface;

interface AccountRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param object $data
     */
    public function getAccount(object $data);
    /**
     * @param object $data
     */
    public function firstAccount(object $data);
    /**
     * @param object $data
     */
    public function get(object $data);

    /**
     * @param $userId
     */
    public function setUserId($userId);

}
