<?php

namespace App\Modules\Person\Repositories\Contracts;

use App\Repository\Eloquent\BaseRepository\Contracts\BaseRepositoryInterface;

interface PersonRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param object $data
     */
    public function get(object $data);

}
