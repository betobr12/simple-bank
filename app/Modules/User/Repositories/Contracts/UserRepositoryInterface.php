<?php

namespace App\Modules\User\Repositories\Contracts;

use App\Repository\Eloquent\BaseRepository\Contracts\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param object $data
     */

    public function get(object $data);
    /**
     * @param object $data
     */
    public function getUser(object $data);
    /**
     * @param object $data
     */
    public function firstUser(object $data);
    /**
     * @param object $data
     */
    public function getUserPaginate(object $data);

}
