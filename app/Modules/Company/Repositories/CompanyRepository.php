<?php

namespace App\Modules\Company\Repositories;

use App\Company;
use App\Repository\Eloquent\BaseRepository\BaseRepository;
use App\Modules\Company\Repositories\Contracts\CompanyRepositoryInterface;

class CompanyRepository extends BaseRepository implements CompanyRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Company());
    }

    /**
     * @param $data
     */
    protected function parameters($data)
    {
        $this->model->id = $data->id ?? null;
        $this->model->account_id = $data->account_id ?? null;
        $this->model->user_id = $data->user_id ?? null;
        $this->model->user_id = $data->user_id ?? null;
        $this->model->social_reason = $data->social_reason ?? null;
        $this->model->fantasy_name = $data->fantasy_name ?? null;
        $this->model->onlyActive = $data->onlyActive ?? 1;
    }

    /**
     * @param object $data
     * @return mixed
     */
    public function getCompany(object $data)
    {
        $this->parameters($data);
        return $this->model->getCompany() ?? [];
    }

    /**
     * @param object $data
     * @return mixed
     */
    public function firstCompany(object $data)
    {
        $this->parameters($data);
        return $this->model->firstCompany() ?? [];
    }

    /**
     * @param object $data
     * @return mixed
     */
    public function getCompanyPaginate(object $data)
    {
        $this->parameters($data);
        return $this->model->getCompanyPaginate() ?? [];
    }
}
