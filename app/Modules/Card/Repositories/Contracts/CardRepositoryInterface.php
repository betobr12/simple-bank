<?php

namespace App\Modules\Card\Repositories\Contracts;

use App\Repository\Eloquent\BaseRepository\Contracts\BaseRepositoryInterface;

interface CardRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param object $data
     */
    public function getCard(object $data);
    /**
     * @param object $data
     */
    public function get(object $data);

    /**
     * @param object $data
     */
    public function firstCard(object $data);

}
