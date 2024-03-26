<?php

namespace App\Modules\Company\Repositories\Contracts;

use App\Repository\Eloquent\BaseRepository\Contracts\BaseRepositoryInterface;

interface CompanyRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param object $data
     */
    public function getCompany(object $data);
    /**
     * @param object $data
     */
    public function firstCompany(object $data);
    /**
     * @param object $data
     */
    public function getCompanyPaginate(object $data);

}
