<?php

namespace App\Modules\Account\Repositories;

use App\Account;
use App\Repository\Eloquent\BaseRepository\BaseRepository;
use App\Modules\Account\Repositories\Contracts\AccountRepositoryInterface;

class AccountRepository extends BaseRepository implements AccountRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Account());
    }

    /**
     * @param $data
     */
    protected function parameters($data)
    {
        $this->model->id = $data->id ?? null;
        $this->model->account_id = $data->account_id ?? null;
        $this->model->user_id = $data->user_id ?? null;
        $this->model->cpf_cnpj = $data->cpf_cnpj ?? null;
        $this->model->number_account = $data->number_account ?? null;
        $this->model->type_id = $data->type_id ?? null;
        $this->model->agency = $data->agency ?? null;
        $this->model->digit = $data->digit ?? null;
        $this->model->onlyActive = $data->onlyActive ?? 1;
    }

    /**
     * @param object $data
     * @return mixed
     */
    public function getAccount(object $data)
    {
        $this->parameters($data);
        return $this->model->getAccount() ?? [];
    }

    /**
     * @param object $data
     * @return mixed
     */
    public function firstAccount(object $data)
    {
        $this->parameters($data);
        return $this->model->firstAccount() ?? [];
    }

    /**
     * @param object $data
     * @return mixed
     */
    public function get(object $data)
    {
        $this->parameters($data);
        return $this->model->get() ?? [];
    }

}
